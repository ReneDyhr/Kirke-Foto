<?php

declare(strict_types=1);

use App\Livewire\ChurchPage;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class);
Route::get('/kirke/{parish}/{church}', ChurchPage::class);
Route::get('/images/church/{path}', [App\Http\Controllers\ChurchImageController::class, 'show'])->where('path', '.*');
