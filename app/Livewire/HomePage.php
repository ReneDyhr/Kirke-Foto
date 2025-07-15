<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Church;
use App\Models\Deanery;
use App\Models\Diocese;
use App\Models\Parish;
use Livewire\Component;

class HomePage extends Component
{
    public function render(): \Illuminate\View\View
    {
        $churches = Church::select(['id', 'url', 'name', 'description', 'seo_tags', 'seo_description', 'parish_id', 'latitude', 'longitude', 'drone_approval', 'open_area', 'created_at', 'updated_at', 'deleted_at'])->orderBy('name', 'asc')->has('images', '>', 0)->get();

        $parishes = Parish::select(['id', 'url', 'name', 'description', 'deanery_id', 'created_at', 'updated_at', 'deleted_at'])->orderBy('name', 'asc')->has('churches.images', '>', 0)->get();

        $deaneries = Deanery::select(['id', 'name', 'placemark', 'updated_at'])->orderBy('name', 'asc')->has('parishes.churches.images', '>', 0)->get();

        $dioceses = Diocese::select(['id', 'name', 'updated_at'])->orderBy('name', 'asc')->has('deaneries.parishes.churches.images', '>', 0)->get();

        return \view('livewire.home-page')
            ->with([
                'title' => 'Home Page',
                'dioceses' => $dioceses,
                'deaneries' => $deaneries,
                'parishes' => $parishes,
                'churches' => $churches,
            ])
            ->layout('layouts.app');
    }
}
