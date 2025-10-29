<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;

class AboutUsPage extends Component
{
    // ── initial state ────────────────────────────────────────
    public function mount(): void {}

    public function render(): \Illuminate\View\View
    {
        // Build SEO data
        $ogTitle = 'Om os - Kirke-Foto';
        $ogDescription = 'Læs mere om Kirke-Foto blev til, historien bag opstarten.';
        $ogImage = 'https://kirke-foto.dk/me_2.jpeg';
        $metaDescription = 'Læs mere om hvordan Kirke-Foto blev til, historien bag opstarten, hvem René Dyhr er samt hvorfor han tager rundt og tager billeder af Danmarks kirker.';
        $metaKeywords = 'kirke, foto, galleri, billeder, kirke-foto, kirkefoto, billed, kirke-galleri, kirkegalleri, dansk, danmark, om os, om mig, rené dyhr, om kirke-foto';
        $ogUrl = 'https://kirke-foto.dk/om-os';

        return \view('livewire.about-us-page')
            ->layout('layouts.app', [
                'ogTitle' => $ogTitle,
                'ogType' => 'website',
                'ogDescription' => $ogDescription,
                'ogImage' => $ogImage,
                'ogUrl' => $ogUrl,
                'metaDescription' => $metaDescription,
                'metaKeywords' => $metaKeywords,
            ])
            ->title('Om os');
    }
}
