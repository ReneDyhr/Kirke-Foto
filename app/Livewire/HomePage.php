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

    // ── initial state ────────────────────────────────────────
    public function mount(): void
    {
        $this->dioceses  = Diocese::has('deaneries.parishes.churches.images', '>', 0)->orderBy('name')->pluck('name', 'id')->all();
        $this->deaneries = Deanery::has('parishes.churches.images', '>', 0)->orderBy('name')->pluck('name', 'id')->all();
        $this->parishes  = Parish::has('churches.images', '>', 0)->orderBy('name')->pluck('name', 'id')->all();
        $this->churches  = Church::has('images', '>', 0)->orderBy('name')->pluck('name', 'id')->all();
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
        $this->dioceses  = Diocese::has('deaneries.parishes.churches.images', '>', 0)->orderBy('name')->pluck('name', 'id')->all();
        $deaneries = Deanery::has('parishes.churches.images', '>', 0)->orderBy('name');

        if ($this->selectedDiocese !== null) {
            $this->deaneries = $deaneries->where('diocese_id', $this->selectedDiocese)
                ->orderBy('name')->pluck('name', 'id')->all();
        } else {
            $this->deaneries = $deaneries->pluck('name', 'id')->all();
        }

        $parishes  = Parish::has('churches.images', '>', 0)->orderBy('name');
        $churches  = Church::has('images', '>', 0)->orderBy('name');

        if ($this->selectedParish !== null) {
            $this->parishes = $parishes->where('id', $this->selectedParish)
                ->orderBy('name')->pluck('name', 'id')->all();

            $this->churches = $churches->where('parish_id', $this->selectedParish)
                ->orderBy('name')->pluck('name', 'id')->all();
        } else {
            $this->parishes = $parishes->pluck('name', 'id')->all();
            $this->churches = $churches->pluck('name', 'id')->all();
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

        $this->deaneries = Deanery::where('diocese_id', $dioceseId)
            ->orderBy('name')->pluck('name', 'id')->all();

        $this->parishes = Parish::whereIn('deanery_id', \array_keys($this->deaneries))
            ->orderBy('name')->pluck('name', 'id')->all();

        $this->churches = Church::whereIn('parish_id', \array_keys($this->parishes))
            ->orderBy('name')->pluck('name', 'id')->all();
    }

    private function cascadeFromDeanery(?int $deaneryId): void
    {
        $this->reset(['selectedParish', 'selectedChurch']);

        if (!$deaneryId) {
            $this->cascadeFromDiocese($this->selectedDiocese);   // keep diocese filter if any

            return;
        }

        $deanery = Deanery::select(['id', 'diocese_id'])->find($deaneryId);
        $this->forceDiocese($deanery->diocese_id);

        $this->parishes = Parish::where('deanery_id', $deaneryId)
            ->orderBy('name')->pluck('name', 'id')->all();

        $this->churches = Church::whereIn('parish_id', \array_keys($this->parishes))
            ->orderBy('name')->pluck('name', 'id')->all();
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

        $church = Church::where('parish_id', $parishId)
            ->orderBy('name')->pluck('name', 'id')->all();

        if (\count($church) === 1) {
            $this->selectedChurch = \array_key_first($church); // restore after resets
        }

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
        $this->selectedParish  = $parish->id;          // restore after resets
        $this->selectedDeanery = $deanery->id;        // restore after resets
        $this->selectedDiocese = $deanery->diocese_id; // restore after resets
        $this->updateData();
    }

    // ── utilities ───────────────────────────────────────────
    private function restoreAllBelow(): void
    {
        $this->deaneries = Deanery::orderBy('name')->pluck('name', 'id')->all();
        $this->parishes  = Parish::orderBy('name')->pluck('name', 'id')->all();
        $this->churches  = Church::orderBy('name')->pluck('name', 'id')->all();
    }

    private function forceDiocese(int $id): void
    {
        if ($this->selectedDiocese === $id) {
            return;
        }
        $this->selectedDiocese = $id;
        $this->cascadeFromDiocese($id);
    }

    private function forceDeanery(int $id): void
    {
        if ($this->selectedDeanery === $id) {
            return;
        }
        $this->selectedDeanery = $id;
        $this->cascadeFromDeanery($id);
    }

    private function forceParish(int $id): void
    {
        if ($this->selectedParish === $id) {
            return;
        }
        $this->selectedParish = $id;
        $this->cascadeFromParish($id);
    }
}
