<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;

class LoginPage extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        return \view('livewire.admin.login-page')->layout('layouts.admin');
    }
}
