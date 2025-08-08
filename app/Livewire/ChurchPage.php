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
        return \view('livewire.church-page')
            ->layout('layouts.app')
            ->title($this->churchModel->name);
    }
}
