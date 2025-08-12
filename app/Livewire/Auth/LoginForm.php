<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginForm extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public string $errorMessage = '';

    /** @var array<string, string> */
    protected array $rules = [
        'email' => 'required|email',
        'password' => 'required|string|min:4',
    ];

    public function login(): void
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            \request()->session()->regenerate();
            $this->redirect('/admin/', navigate: true);

            return;
        }

        $this->errorMessage = 'Forkert loginoplysninger.';
    }

    public function render(): \Illuminate\View\View
    {
        return \view('livewire.auth.login-form')->layout('layouts.admin');
    }
}
