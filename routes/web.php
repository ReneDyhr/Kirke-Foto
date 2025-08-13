<?php

declare(strict_types=1);

use App\Livewire\AboutUsPage;
use App\Livewire\Admin\AdminPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\ChurchPage;
use App\Livewire\ContactPage;
use App\Livewire\HomePage;
use App\Livewire\MapPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class);
Route::get('/kort', MapPage::class);
Route::get('/om-os', AboutUsPage::class);
Route::get('/kontakt', ContactPage::class);
Route::get('/kirke/{parish}/{church}', ChurchPage::class);
Route::get('/images/church/{path}', [App\Http\Controllers\ChurchImageController::class, 'show'])->where('path', '.*');

// Login page (optional, for direct logins)
// Route::get('/login', LoginPage::class)->name('login');

// Admin entry - shows login inside layout if not authenticated
Route::get('/admin/', AdminPage::class);

// Admin: Church images management
Route::get('/admin/church/{church}/images', App\Livewire\Admin\ChurchImagesPage::class);

// Admin: Church communications management
Route::get('/admin/church/{church}/communications', App\Livewire\Admin\ChurchCommunicationsPage::class);

// Route::post('/logout', function (): RedirectResponse {
//     Auth::logout();
//     \request()->session()->invalidate();
//     \request()->session()->regenerateToken();

//     return \redirect('/');
// })->name('logout');
