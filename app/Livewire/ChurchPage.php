<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Church;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ChurchPage extends Component
{
    public string $parish;

    public string $church;

    public Church $churchModel;

    public array $images = [];

    // ── initial state ────────────────────────────────────────
    public function mount(): void
    {
        $this->churchModel = Church::has('images')->whereHas('parish', function (Builder $query): void {
            $query->where('url', $this->parish);
        })->where('url', $this->church)->firstOrFail();

        $this->images = $this->churchModel->images()->getQuery()->orderBy('sorting', 'asc')->get()->map(function ($image) {
            return [
                'path' => $image->path,
                'description' => '',
                'date_taken' => $image->date_taken,
                'panorama' => $image->panorama,
            ];
        })->toArray();
    }

    public function render(): \Illuminate\View\View
    {
        return \view('livewire.church-page')
            ->layout('layouts.app');
    }
}
