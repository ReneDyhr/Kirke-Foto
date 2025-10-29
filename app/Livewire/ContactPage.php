<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;

class ContactPage extends Component
{
    public int $mathNumber1 = 0;

    public int $mathNumber2 = 0;

    public ?int $mathResult = null;

    public string $name = '';

    public string $email = '';

    public string $subject = '';

    public string $message = '';

    // ── initial state ────────────────────────────────────────
    public function mount(): void
    {
        $this->mathNumber1 = \rand(1, 10);
        $this->mathNumber2 = \rand(1, 10);
    }

    public function render(): \Illuminate\View\View
    {
        return \view('livewire.contact-page')
            ->layout('layouts.app')
            ->title('Kontakt');
    }

    public function send(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'mathResult' => 'required|integer|in:' . ($this->mathNumber1 + $this->mathNumber2),
        ]);

        // Store values before reset
        $name = $this->name;
        $email = $this->email;
        $subject = $this->subject;
        $message = $this->message;

        $this->reset(['name', 'email', 'subject', 'message', 'mathResult']);

        $recipient = \config('mail.contact_recipient', '');
        \Mail::to($recipient)->send(new \App\Mail\ContactMail($name, $email, $subject, $message));
    }
}
