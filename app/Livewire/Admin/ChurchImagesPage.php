<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Church;
use App\Models\ChurchImage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChurchImagesPage extends Component
{
    use WithFileUploads;

    public Church $church;

    /**
     * @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile>
     */
    public array $photos = [];

    public function mount(Church $church): void
    {
        $this->church = $church->load(['images' => function (HasMany $q): void {
            $q->orderBy('sorting');
        }]);
    }

    public function render(): \Illuminate\View\View
    {
        $images = $this->church->images()->orderBy('sorting')->get();

        return \view('livewire.admin.church-images-page', [
            'images' => $images,
        ])->layout('layouts.admin');
    }

    public function storeImages(): void
    {
        if ($this->photos === []) {
            return;
        }

        // Large JPEG decode + resize + remote storage: relax limits for this request only.
        if (\function_exists('ini_set')) {
            @\ini_set('memory_limit', '1024M');
            @\ini_set('max_execution_time', '600');
        }

        $this->validate([
            'photos.*' => 'image|mimes:jpg,jpeg|max:2097152',
        ]);

        DB::transaction(function (): void {
            $disk = Storage::disk('wasabi');

            // Instantiate Intervention Image v3 dynamically to satisfy static analysis
            $imageManagerClass = 'Intervention\\Image\\ImageManager';
            $driverClass = 'Intervention\\Image\\Drivers\\Gd\\Driver';

            /** @var \Intervention\Image\ImageManager $manager */
            $manager = new $imageManagerClass(new $driverClass());

            $max = $this->church->images()->max('sorting');
            $nextSort = \is_int($max) ? $max : 0;

            foreach ($this->photos as $file) {
                $originalName = $file->getClientOriginalName();
                $panorama = \str_contains($originalName, '360_');
                $drone = \str_contains($originalName, 'DJI_');

                $filename = $file->hashName();
                $lower = Str::lower($filename);

                if (!Str::endsWith($lower, ['.jpg', '.jpeg'])) {
                    $filename .= '.jpg';
                }

                // EXIF date (requires ext-exif)
                $dateTaken = null;
                $exif = (\function_exists('exif_read_data') ? @\exif_read_data($file->getRealPath()) : false) ?: [];
                $dtoStr = \array_key_exists('DateTimeOriginal', $exif) ? $exif['DateTimeOriginal'] : null;

                if (\is_string($dtoStr) && $dtoStr !== '') {
                    try {
                        $dt = Carbon::createFromFormat('Y:m:d H:i:s', $dtoStr);

                        if ($dt instanceof Carbon) {
                            $dateTaken = $dt->format('Y-m-d');
                        }
                    } catch (\Throwable) {
                        $dateTaken = null;
                    }
                }

                // Process high-res (large decodes need plenty of RAM; release before second read)
                $high = $manager->read($file->getRealPath());
                $high->scaleDown(width: $panorama ? 4000 : 1500);
                $highBinary = (string) $high->toJpeg(85);
                unset($high);

                $thumb = $manager->read($file->getRealPath());
                $thumb->cover(500, 400);
                $thumbBinary = (string) $thumb->toJpeg(80);
                unset($thumb);

                // Store on Wasabi (public)
                $disk->put('church-images/high_' . $filename, $highBinary, ['visibility' => 'public']);
                $disk->put('church-images/thumb_' . $filename, $thumbBinary, ['visibility' => 'public']);

                ChurchImage::create([
                    'church_id' => $this->church->id,
                    'name' => $originalName,
                    'path' => $filename,
                    'drone' => $drone,
                    'panorama' => $panorama,
                    'sorting' => $nextSort++,
                    'date_taken' => $dateTaken,
                ]);
            }
        });

        $this->church->refresh();

        // Send updated images back to the browser so Alpine can refresh
        $updated = $this->church->images()->orderBy('sorting')->get(['id', 'path', 'name'])
            ->map(fn(ChurchImage $i): array => ['id' => $i->id, 'path' => $i->path, 'name' => $i->name])
            ->values()->all();
        $this->dispatch('images-updated', images: $updated);

        $this->photos = [];
        $this->dispatch('notify', message: 'Billeder uploadet');
    }

    public function deleteImage(int $id): void
    {
        $image = $this->church->images()->whereKey($id)->first();

        if ($image === null) {
            return;
        }

        $disk = Storage::disk('wasabi');
        $disk->delete('church-images/high_' . $image->path);
        $disk->delete('church-images/thumb_' . $image->path);

        $image->delete();

        $this->church->refresh();
    }

    /**
     * @param array<int, int> $orderedIds
     */
    public function sortImages(array $orderedIds): void
    {
        $validIds = $this->church->images()->pluck('id')->all();

        /** @var array<int, int> $orderMap */
        $orderMap = \array_values(\array_filter($orderedIds, fn(int $id): bool => \in_array($id, $validIds, true)));

        foreach ($orderMap as $index => $id) {
            ChurchImage::whereKey($id)->update(['sorting' => $index]);
        }

        $this->church->refresh();
    }
}
