<?php

namespace App\Livewire\Programs;

use App\Models\Program;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ProgramsIndex extends Component
{
    use WithPagination;

    public $programId;
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

    #[On('reloadPrograms')]
    public function reloadPage()
    {
        $this->resetPage();
    }

    public function edit($programId)
    {
        if ($program = Program::find($programId)) {
            $this->dispatch('openEditModal', ['program' => $program]);
        }
    }

    public function delete($programId)
    {
        $this->programId = $programId;
        Flux::modal('delete-program')->show();
    }

    public function destroy()
    {
        $program = Program::findOrFail($this->programId);
        $program->delete();

        Flux::modal('delete-program')->close();
        $this->dispatch('showSuccessAlert', message: 'تم حذف البرنامج بنجاح');
        $this->resetPage();
    }

    public function render()
    {
        $programs = Program::query()
            ->when(
                $this->term,
                fn($q) =>
                $q->where('name', 'like', '%' . $this->term . '%')
                    ->orWhere('description', 'like', '%' . $this->term . '%')
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->latest('created_at')
            ->paginate(10);

        return view('livewire.programs.programs-index', compact('programs'));
    }
}
