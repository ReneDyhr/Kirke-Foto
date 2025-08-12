<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Livewire\Component;

class LoginPage extends Component
{
    public function render(): \Illuminate\View\View
    {
        return \view('livewire.auth.login-page')->layout('layouts.admin');
    }
}
