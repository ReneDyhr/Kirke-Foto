<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Church;
use App\Models\ChurchCommunication;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ChurchCommunicationsPage extends Component
{
    public Church $church;

    public string $subject = '';

    public string $message = '';

    public ?string $sent_at = null; // datetime-local string

    public function mount(Church $church): void
    {
        $this->church = $church->load(['communications' => function (HasMany $q): void {
            $q->latest();
        }]);
    }

    public function render(): \Illuminate\View\View
    {
        $communications = $this->church->communications()->orderByDesc('sent_at')->get();

        return \view('livewire.admin.church-communications-page', [
            'communications' => $communications,
        ]);
    }

    public function addCommunication(): void
    {
        $data = $this->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'sent_at' => 'nullable',
        ]);

        ChurchCommunication::create([
            'church_id' => $this->church->id,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'sent_at' => $data['sent_at'] ?? null,
        ]);

        $this->reset(['subject', 'message', 'sent_at']);
        $this->church->refresh();

        $this->dispatch('notify', message: 'Kommunikation tilføjet');
    }

    public function deleteCommunication(int $id): void
    {
        $comm = $this->church->communications()->whereKey($id)->first();

        if ($comm === null) {
            return;
        }

        $comm->delete();
        $this->church->refresh();
        $this->dispatch('notify', message: 'Kommunikation slettet');
    }
}
