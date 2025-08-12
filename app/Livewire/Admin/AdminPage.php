<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Church;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminPage extends Component
{
    /** @var \Illuminate\Support\Collection<int, \App\Models\Church> */
    public Collection $churches;

    public function mount(): void
    {
        $this->churches = Church::query()
            ->with([
                'parish:id,name,deanery_id',
                'parish.deanery:id,name,diocese_id',
                'parish.deanery.diocese:id,name',
                'images:id,church_id',
                'communications:id,church_id,subject,message,sent_at,updated_at',
            ])
            // ->select(['id', 'name', 'drone_approval', 'open_area', 'contact_later', 'seo_description', 'seo_tags', 'parish_id'])
            ->orderBy('name')
            ->get();
    }

    public function logout(): void
    {
        Auth::logout();
        \request()->session()->invalidate();
        \request()->session()->regenerateToken();
        $this->redirect('/', navigate: true);
    }

    public function render(): \Illuminate\View\View
    {
        return \view('livewire.admin.admin-page')
            ->layout('layouts.admin');
    }
}
