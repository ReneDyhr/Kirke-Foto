<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Church;
use App\Models\ChurchImage;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ChurchPage extends Component
{
    public string $parish;

    public string $church;

    public Church $churchModel;

    /**
     * @var array<int, array{path: string, description: string, date_taken: ?string, panorama: bool}>
     */
    public array $images = [];

    // ── initial state ────────────────────────────────────────
    public function mount(): void
    {
        $this->churchModel = Church::has('images')->whereHas('parish', function (Builder $query): void {
            $query->where('url', $this->parish);
        })->where('url', $this->church)->firstOrFail();

        /**
         * @var array<int, array{path: string, description: string, date_taken: ?string, panorama: bool}>
         */
        $images = $this->churchModel->images()->getQuery()->orderBy('sorting', 'asc')->get()->map(function (ChurchImage $image): array {
            return [
                'path' => $image->path,
                'description' => '',
                'date_taken' => $image->date_taken,
                'panorama' => $image->panorama,
            ];
        })->toArray();

        $this->images = $images;
    }

    public function render(): \Illuminate\View\View
    {
        // Get first image for og:image if available
        $firstImage = $this->churchModel->images()->orderBy('sorting', 'asc')->first();
        $ogImage = 'https://kirke-foto.dk/images/church/high_P4Ai1Hv65iGQfrnPlXO70XP8TBch4wtcwlrL1DQ3.jpg';

        if ($firstImage !== null && !empty($firstImage->path)) {
            $ogImage = 'https://kirke-foto.dk/images/church/high_' . $firstImage->path;
        }

        // Build SEO data from church model
        $ogTitle = $this->churchModel->name . ' - Kirke-Foto';
        $ogDescription = $this->churchModel->seo_description ?? 'Et billedgalleri over de danske kirker både fra landjorden og luften.';
        $metaDescription = $this->churchModel->seo_description ?? 'Et billedgalleri over de danske kirker både fra landjorden og luften. Søg nemt imellem stifter, provstier, sogne og kirker og find netop din kirke frem.';
        $metaKeywords = $this->churchModel->seo_tags ?? 'kirke, foto, galleri, billeder, kirke-foto, kirkefoto, billed, kirke-galleri, kirkegalleri, dansk, danmark';
        $ogUrl = 'https://kirke-foto.dk/kirke/' . $this->parish . '/' . $this->church;

        return \view('livewire.church-page')
            ->layout('layouts.app', [
                'ogTitle' => $ogTitle,
                'ogType' => 'website',
                'ogDescription' => $ogDescription,
                'ogImage' => $ogImage,
                'ogUrl' => $ogUrl,
                'metaDescription' => $metaDescription,
                'metaKeywords' => $metaKeywords,
            ])
            ->title($this->churchModel->name);
    }
}
