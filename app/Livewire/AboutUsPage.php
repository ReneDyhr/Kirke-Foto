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
        return \view('livewire.about-us-page')
            ->layout('layouts.app')
            ->title('Om os');
    }
}
