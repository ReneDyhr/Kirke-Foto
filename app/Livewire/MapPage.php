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
        // Build SEO data
        $ogTitle = 'Kort - Kirke-Foto';
        $ogDescription = 'Her kan du se et Danmarks kort over de kirker der er blevet fotograferet til Kirke-Foto.dk og nemt klikke ind og se billederne.';
        $ogImage = 'https://kirke-foto.dk/images/church/high_P4Ai1Hv65iGQfrnPlXO70XP8TBch4wtcwlrL1DQ3.jpg';
        $metaDescription = 'Se et Danmarks kort over kirker der ligger på Kirke-Foto.dk';
        $metaKeywords = 'kirke, foto, galleri, billeder, kirke-foto, kirkefoto, billed, kirke-galleri, kirkegalleri, dansk, danmark, kort, danmarkskort, kort over kirker';
        $ogUrl = 'https://kirke-foto.dk/kort';

        return \view('livewire.map-page')
            ->layout('layouts.app', [
                'ogTitle' => $ogTitle,
                'ogType' => 'website',
                'ogDescription' => $ogDescription,
                'ogImage' => $ogImage,
                'ogUrl' => $ogUrl,
                'metaDescription' => $metaDescription,
                'metaKeywords' => $metaKeywords,
            ])
            ->title('Kort');
    }
}
