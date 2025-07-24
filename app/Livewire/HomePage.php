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
    public int $totalChurches = 0; // total number of churches with images

    public int $totalDeaneries = 0; // total number of deaneries with images

    public int $totalDroneAccepted = 0; // total drone images accepted

    // ── option lists ──────────────────────────────────────────
    public array $dioceses  = [];   // [id => name]

    public array $deaneries = [];

    public array $parishes  = [];

    public array $churches  = [];

    // ── current selections ───────────────────────────────────
    public ?int $selectedDiocese = null;

    public ?int $selectedDeanery = null;

    public ?int $selectedParish  = null;

    public ?int $selectedChurch  = null;

    public Church $selectedChurchModel;

    // ── initial state ────────────────────────────────────────
    public function mount(): void
    {
        $this->restoreAllBelow();

        $this->totalChurches = Church::has('images')->count();
        $this->totalDeaneries = Deanery::has('parishes.churches.images')->count();
        $this->totalDroneAccepted = Church::where('drone_approval', '=', 1)->count();
    }

    // ── watchers ─────────────────────────────────────────────
    public function updatedSelectedDiocese(?int $id): void
    {
        $this->cascadeFromDiocese($id);
    }

    public function updatedSelectedDeanery(?int $id): void
    {
        $this->cascadeFromDeanery($id);
    }

    public function updatedSelectedParish(?int $id): void
    {
        $this->cascadeFromParish($id);
    }

    public function updatedSelectedChurch(?int $id): void
    {
        $this->cascadeFromChurch($id);
    }

    public function render(): \Illuminate\View\View
    {
        return \view('livewire.home-page')
            ->layout('layouts.app');
    }

    private function updateData(): void
    {
        $this->js('setTimeout(function() { window.SelectBind(); }, 50);');
        // $dioceses  = Diocese::has('deaneries.parishes.churches.images', '>', 0)->orderBy('name')->pluck('name', 'id')->all();
        $deaneries = Deanery::has('parishes.churches.images', '>', 0)->orderBy('name');
        $parishes  = Parish::has('churches.images', '>', 0)->orderBy('name');
        $churches  = Church::has('images', '>', 0)->orderBy('name');

        if ($this->selectedDiocese !== null) {
            $this->deaneries = $deaneries->where('diocese_id', $this->selectedDiocese)
                ->orderBy('name')->pluck('name', 'id')->all();
            $parishes = $parishes->whereIn('deanery_id', $deaneries->pluck('id'))->orderBy('name');
            $churches = $churches->whereIn('parish_id', $parishes->pluck('id'))->orderBy('name');
        } else {
            $this->deaneries = $deaneries->pluck('name', 'id')->all();
        }

        if ($this->selectedDeanery !== null) {
            $this->deaneries = $deaneries->where('diocese_id', $this->selectedDiocese)
                ->orderBy('name')->pluck('name', 'id')->all();
            $churches = $churches->whereIn('parish_id', $parishes->where('deanery_id', $this->selectedDeanery)->pluck('id'))->orderBy('name');
        } else {
            $this->deaneries = $deaneries->pluck('name', 'id')->all();
        }

        if ($this->selectedParish !== null) {
            $this->parishes = $parishes->where('id', $this->selectedParish)
                ->orderBy('name')->pluck('name', 'id')->all();

            $this->churches = $churches->where('parish_id', $this->selectedParish)
                ->orderBy('name')->pluck('name', 'id')->all();
        } else {
            $this->parishes = $parishes->pluck('name', 'id')->all();
            $this->churches = $churches->pluck('name', 'id')->all();
        }

        if (\count($this->deaneries) === 1) {
            $this->selectedDeanery = \array_key_first($this->deaneries); // restore after resets
        }

        if (\count($this->parishes) === 1) {
            $this->selectedParish = \array_key_first($this->parishes); // restore after resets
        }

        if (\count($this->churches) === 1) {
            $this->selectedChurch = \array_key_first($this->churches); // restore after resets
            $this->selectedChurchModel = Church::findOrFail($this->selectedChurch);
        }
    }

    // ── cascade helpers ─────────────────────────────────────
    private function cascadeFromDiocese(?int $dioceseId): void
    {
        $this->reset(['selectedDeanery', 'selectedParish', 'selectedChurch']);

        if ($dioceseId === null) {
            $this->restoreAllBelow();

            return;
        }

        $this->selectedDiocese = $dioceseId; // restore after resets
        $this->updateData();
    }

    private function cascadeFromDeanery(?int $deaneryId): void
    {
        $this->reset(['selectedParish', 'selectedChurch']);

        if ($deaneryId === null) {
            $this->cascadeFromDiocese($this->selectedDiocese);   // keep diocese filter if any

            return;
        }

        $deanery = Deanery::select(['id', 'diocese_id'])->findOrFail($deaneryId);
        $this->selectedDiocese = $deanery->diocese_id; // restore after resets
        $this->updateData();
    }

    private function cascadeFromParish(?int $parishId): void
    {
        $this->reset(['selectedChurch']);

        if ($parishId === null) {
            $this->cascadeFromDeanery($this->selectedDeanery);

            return;
        }

        $parish  = Parish::select(['id', 'deanery_id'])->findOrFail($parishId);
        $deanery = Deanery::select(['id', 'diocese_id'])->findOrFail($parish->deanery_id);

        $this->selectedDeanery = $deanery->id;        // restore after resets
        $this->selectedDiocese = $deanery->diocese_id; // restore after resets

        $this->updateData();
    }

    private function cascadeFromChurch(?int $churchId): void
    {
        if ($churchId === null) {
            return;
        }
        $church  = Church::select('id', 'parish_id')->findOrFail($churchId);
        $parish  = Parish::select('id', 'deanery_id')->findOrFail($church->parish_id);
        $deanery = Deanery::select('id', 'diocese_id')->findOrFail($parish->deanery_id);

        $this->selectedChurch = $churchId;          // restore after resets
        $this->selectedChurchModel = $church; // restore after resets
        $this->selectedParish  = $parish->id;          // restore after resets
        $this->selectedDeanery = $deanery->id;        // restore after resets
        $this->selectedDiocese = $deanery->diocese_id; // restore after resets
        $this->updateData();
    }

    // ── utilities ───────────────────────────────────────────
    private function restoreAllBelow(): void
    {
        $this->dioceses = Diocese::has('deaneries.parishes.churches.images', '>', 0)->orderBy('name')->pluck('name', 'id')->all();
        $this->deaneries = Deanery::has('parishes.churches.images', '>', 0)->orderBy('name')->pluck('name', 'id')->all();
        $this->parishes  = Parish::has('churches.images', '>', 0)->orderBy('name')->pluck('name', 'id')->all();
        $this->churches  = Church::has('images', '>', 0)->orderBy('name')->pluck('name', 'id')->all();
    }
}
