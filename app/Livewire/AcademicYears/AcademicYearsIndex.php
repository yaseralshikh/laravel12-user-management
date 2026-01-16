<?php

namespace App\Livewire\AcademicYears;

use App\Models\AcademicYear;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AcademicYearsIndex extends Component
{
    use WithPagination;

    public $academicYearId;
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

    #[On('reloadAcademicYears')]
    public function reloadPage()
    {
        $this->resetPage();
    }

    public function edit($academicYearId)
    {
        if ($academicYear = AcademicYear::find($academicYearId)) {
            $this->dispatch('openEditModal', ['academicYear' => $academicYear]);
        }
    }

    public function delete($academicYearId)
    {
        $this->academicYearId = $academicYearId;
        Flux::modal('delete-academic-year')->show();
    }

    public function destroy()
    {
        $academicYear = AcademicYear::findOrFail($this->academicYearId);
        $academicYear->delete();

        Flux::modal('delete-academic-year')->close();
        $this->dispatch('showSuccessAlert', message: 'تم حذف السنة الأكاديمية بنجاح');
        $this->resetPage();
    }

    public function render()
    {
        $academicYears = AcademicYear::query()
            ->when(
                $this->term,
                fn($q) =>
                $q->where('name', 'like', '%' . $this->term . '%')
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->latest('created_at')
            ->paginate(10);

        return view('livewire.academic-years.academic-years-index', compact('academicYears'));
    }
}
