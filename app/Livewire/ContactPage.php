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
        // Build SEO data
        $ogTitle = 'Kontakt - Kirke-Foto';
        $ogDescription = 'Få kontakt til Kirke-Foto.';
        $ogImage = 'https://kirke-foto.dk/me_2.jpeg';
        $metaDescription = 'Vil du gerne i kontakt med Kirke-Foto? Så har du her muligheden for at sende en besked.';
        $metaKeywords = 'kirke, foto, galleri, billeder, kirke-foto, kirkefoto, billed, kirke-galleri, kirkegalleri, dansk, danmark, kontakt, kontakt rené dyhr, kontakt kirke-foto';
        $ogUrl = 'https://kirke-foto.dk/kontakt';

        return \view('livewire.contact-page')
            ->layout('layouts.app', [
                'ogTitle' => $ogTitle,
                'ogType' => 'website',
                'ogDescription' => $ogDescription,
                'ogImage' => $ogImage,
                'ogUrl' => $ogUrl,
                'metaDescription' => $metaDescription,
                'metaKeywords' => $metaKeywords,
            ])
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
