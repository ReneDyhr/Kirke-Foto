<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;

class ChurchPage extends Component
{
    // ── initial state ────────────────────────────────────────
    public function mount(): void {}

    public function render(): \Illuminate\View\View
    {
        return \view('livewire.church-page')
            ->layout('layouts.app');
    }
}
