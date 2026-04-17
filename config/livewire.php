<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Livewire Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for Livewire components.
    |
     */

    'temporary_file_upload' => [
        'disk' => null,
        'rules' => 'file|max:2097152', // 2GB in kilobytes
        'directory' => null,
        'middleware' => 'throttle:60,1',
        'max_upload_time' => 20, // minutes
    ],
];
