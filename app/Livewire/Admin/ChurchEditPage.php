<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Church;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ChurchEditPage extends Component
{
    public Church $church;

    public string $seo_description = '';

    public string $seo_tags = '';

    public bool $drone_approval = false;

    public bool $open_area = false;

    public bool $contact_later = false;

    public function mount(Church $church): void
    {
        $this->church = $church;
        $this->seo_description = (string) ($church->seo_description ?? '');
        $this->seo_tags = (string) ($church->seo_tags ?? '');
        $this->drone_approval = (bool) $church->drone_approval;
        $this->open_area = (bool) $church->open_area;
        $this->contact_later = (bool) $church->contact_later;
    }

    public function render(): \Illuminate\View\View
    {
        return \view('livewire.admin.church-edit-page');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'seo_description' => 'nullable|string|max:255',
            'seo_tags' => 'nullable|string|max:255',
            'drone_approval' => 'boolean',
            'open_area' => 'boolean',
            'contact_later' => 'boolean',
        ]);

        $this->church->fill($validated);
        $this->church->save();

        $this->dispatch('notify', message: 'Kirke opdateret');
        $this->redirect('/admin/', navigate: true);
    }
}
