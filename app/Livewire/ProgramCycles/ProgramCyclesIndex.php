<?php

namespace App\Livewire\ProgramCycles;

use App\Models\ProgramCycle;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ProgramCyclesIndex extends Component
{
    use WithPagination;

    public $programCycleId;
    public $term = '';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public function updatedTerm()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    #[On('reloadProgramCycles')]
    public function reloadPage()
    {
        $this->resetPage();
    }

    public function edit($programCycleId)
    {
        if ($programCycle = ProgramCycle::with('program', 'academicYear')->find($programCycleId)) {
            $this->dispatch('openEditModal', ['programCycle' => $programCycle]);
        }
    }

    public function delete($programCycleId)
    {
        $this->programCycleId = $programCycleId;
        Flux::modal('delete-program-cycle')->show();
    }

    public function destroy()
    {
        $programCycle = ProgramCycle::findOrFail($this->programCycleId);
        $programCycle->delete();

        Flux::modal('delete-program-cycle')->close();
        $this->dispatch('showSuccessAlert', message: 'تم حذف دورة البرنامج بنجاح');
        $this->resetPage();
    }

    public function render()
    {
        $programCycles = ProgramCycle::with('program', 'academicYear')
            ->when(
                $this->term,
                fn($q) =>
                $q->whereHas('program', fn($p) => $p->where('name', 'like', '%' . $this->term . '%'))
                    ->orWhereHas('academicYear', fn($a) => $a->where('name', 'like', '%' . $this->term . '%'))
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->latest('created_at')
            ->paginate(10);

        return view('livewire.program-cycles.program-cycles-index', compact('programCycles'));
    }
}
