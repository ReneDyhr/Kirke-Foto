<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Church;
use Livewire\Component;

class MapPage extends Component
{
    /** @var list<array{name: string, id: int, url: string, latitude: float, longitude: float, parish: string, parish_url: string, deanery: string, diocese: string}> */
    public array $churches  = [];

    public function mount(): void
    {
        // @phpstan-ignore assign.propertyType
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
                'diocese' => $church->parish->deanery->diocese->name ?? '',
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
