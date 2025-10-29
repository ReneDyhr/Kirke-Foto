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
        'rules' => 'file|max:102400', // 100MB in kilobytes
    ],
];
