<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Church;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MapPage extends Component
{
    /** @var list<array{name: string, id: int, url: string, latitude: float, longitude: float, parish: string, parish_url: string, deanery: string, diocese: string, open_area: bool, drone_approval: bool}> */
    public array $kirker = [];

    /** @var list<array{name: string, id: int, url: string, latitude: float, longitude: float, parish: string, parish_url: string, deanery: string, diocese: string}> */
    public array $finished = [];

    /** @var list<array{name: string, id: int, url: string, latitude: float, longitude: float, parish: string, parish_url: string, deanery: string, diocese: string, date: null|string, old: bool, contact_later: bool}> */
    public array $contacted = [];

    /** @var list<array{name: string, id: int, url: string, latitude: float, longitude: float, parish: string, parish_url: string, deanery: string, diocese: string, open_area: bool, drone_approval: bool}> */
    public array $fadedChurches = [];

    public function mount(): void
    {
        // $finished: Churches with images containing "DJI" in the name
        // @phpstan-ignore assign.propertyType
        $this->finished = Church::with(['parish.deanery.diocese'])
            ->whereExists(function (Builder $query): void {
                $query->select(DB::raw(1))
                    ->from('church_images')
                    ->whereColumn('church_images.church_id', 'churches.id')
                    ->where('church_images.name', 'like', '%DJI%');
            })
            ->get()
            ->map(function (Church $church): array {
                return $this->churchToArray($church);
            })
            ->values()
            ->toArray();

        // $contacted: Churches that have been contacted, with drone_approval = 0 and open_area = 0
        $contactedChurches = Church::with(['parish.deanery.diocese'])
            ->where('drone_approval', 0)
            ->where('open_area', 0)
            ->whereExists(function (Builder $query): void {
                $query->select(DB::raw(1))
                    ->from('church_communications')
                    ->whereColumn('church_communications.church_id', 'churches.id');
            })
            ->get();

        // @phpstan-ignore assign.propertyType
        $this->contacted = $contactedChurches->map(function (Church $church): array {
            // Get the most recent communication
            $latestCommunication = $church->communications()
                ->orderByDesc('sent_at')
                ->first();

            $date = ($latestCommunication !== null && $latestCommunication->sent_at !== null)
                ? $latestCommunication->sent_at->format('Y-m-d H:i:s')
                : null;
            $old = false;

            if ($latestCommunication !== null && $latestCommunication->sent_at !== null) {
                $daysDiff = \now()->diffInDays($latestCommunication->sent_at, false);
                $old = $daysDiff < -30;
            }

            return \array_merge($this->churchToArray($church), [
                'date' => $date,
                'old' => $old,
                'contact_later' => $church->contact_later,
            ]);
        })->values()
            ->toArray();

        // $kirker: Churches with (drone_approval = 1 OR open_area = 1) AND no "DJI" images
        // @phpstan-ignore assign.propertyType
        $this->kirker = Church::with(['parish.deanery.diocese'])
            ->where(function (\Illuminate\Database\Eloquent\Builder $query): void {
                $query->where('drone_approval', 1)
                    ->orWhere('open_area', 1);
            })
            ->whereNotExists(function (Builder $query): void {
                $query->select(DB::raw(1))
                    ->from('church_images')
                    ->whereColumn('church_images.church_id', 'churches.id')
                    ->where('church_images.name', 'like', '%DJI%');
            })
            ->get()
            ->map(function (Church $church): array {
                return $this->churchToArray($church);
            })
            ->values()
            ->toArray();

        $shownIds = \collect($this->kirker)
            ->pluck('id')
            ->merge(\collect($this->finished)->pluck('id'))
            ->merge(\collect($this->contacted)->pluck('id'))
            ->unique()
            ->values()
            ->all();

        $fadedQuery = Church::with(['parish.deanery.diocese'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($shownIds !== []) {
            $fadedQuery->whereNotIn('id', $shownIds);
        }

        // @phpstan-ignore assign.propertyType
        $this->fadedChurches = $fadedQuery->get()
            ->map(function (Church $church): array {
                return $this->churchToArray($church);
            })
            ->values()
            ->toArray();
    }

    public function render(): \Illuminate\View\View
    {
        return \view('livewire.admin.map-page')
            ->layout('layouts.admin')
            ->title('Kort');
    }

    /**
     * @return array{name: string, id: int, url: string, latitude: float, longitude: float, parish: string, parish_url: string, deanery: string, diocese: string, open_area: bool, drone_approval: bool}
     */
    private function churchToArray(Church $church): array
    {
        return [
            'name' => $church->name,
            'id' => $church->id,
            'url' => $church->url,
            'latitude' => $church->latitude,
            'longitude' => $church->longitude,
            'parish' => $church->parish->name ?? '',
            'parish_url' => $church->parish->url ?? '',
            'deanery' => $church->parish->deanery->name ?? '',
            'diocese' => $church->parish->deanery->diocese->name ?? '',
            'open_area' => $church->open_area,
            'drone_approval' => $church->drone_approval,
        ];
    }
}
