<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

// Schedule a task to run every day except friday at 15:00
Schedule::call(function (): void {
    $date = \now()->addDay()->format('Y-m-d');
    Artisan::call('app:generate-rooms-image', ['date' => $date]);
    Artisan::call('app:generate-support-image', ['date' => $date]);
})->cron('0 15 * * 1-4')->timezone('Europe/Copenhagen');

// Schedule a task to run every Friday at 14:00
Schedule::call(function (): void {
    $date = \now()->addDays(3)->format('Y-m-d');
    Artisan::call('app:generate-rooms-image', ['date' => $date]);
    Artisan::call('app:generate-support-image', ['date' => $date]);
})->cron('0 14 * * 5')->timezone('Europe/Copenhagen');
