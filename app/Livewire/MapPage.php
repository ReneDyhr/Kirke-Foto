<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Church;
use Livewire\Component;

class MapPage extends Component
{
    public array $churches  = [];

    public function mount(): void
    {
        $this->churches  = Church::has('images', '>', 0)->orderBy('name')->get()->map(function (Church $church): array {
            return [
                'name' => $church->name,
                'id' => $church->id,
                'url' => $church->url,
                'latitude' => $church->latitude,
                'longitude' => $church->longitude,
                'parish' => $church->parish->name ?? '',
                'parish_url' => $church->parish->url ?? '',
                'deanery' => $church->parish->deanery->name ?? '',
                'deanery_url' => $church->parish->deanery->url ?? '',
                'diocese' => $church->parish->deanery->diocese->name ?? '',
                'diocese_url' => $church->parish->deanery->diocese->url ?? '',
            ];
        })->toArray();
    }

    public function render(): \Illuminate\View\View
    {
        return \view('livewire.map-page')
            ->layout('layouts.app')
            ->title('Kort');
    }
}
