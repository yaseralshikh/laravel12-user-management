<?php

namespace App\Livewire\Visits;

use App\Models\Visit;
use App\Models\School;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\ProgramCycle;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class VisitCreate extends Component
{
    #[Validate('required|exists:schools,id')]
    public $school_id = '';

    #[Validate('required|exists:users,id')]
    public $user_id = '';

    #[Validate('required|exists:academic_years,id')]
    public $academic_year_id = '';

    #[Validate('nullable|exists:program_cycles,id')]
    public $program_cycle_id = '';

    #[Validate('required|date')]
    public $visit_date;

    #[Validate('required|string')]
    public $visit_type = '';

    #[Validate('nullable|string')]
    public $objective = '';

    #[Validate('nullable|string')]
    public $notes = '';

    #[Validate('nullable|string')]
    public $recommendations = '';

    public function render()
    {
        return view('livewire.visits.visit-create', [
            'schools' => School::where('status', 'active')->orderBy('name')->get(),
            'users' => User::where('status', 'active')->orderBy('name')->get(),
            'academicYears' => AcademicYear::where('status', 'active')->orderBy('name')->get(),
            'programCycles' => ProgramCycle::where('status', 'active')->with('program')->orderBy('term')->get(),
            'visitTypes' => [
                'فنية' => 'فنية',
                'إدارية' => 'إدارية',
                'تقويمية' => 'تقويمية',
                'متابعة' => 'متابعة',
                'أخرى' => 'أخرى'
            ],
        ]);
    }

    #[On('openCreateModal')]
    public function resetForm()
    {
        $this->reset();
        $this->visit_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        try {
            Visit::create([
                'school_id' => $this->school_id,
                'user_id' => $this->user_id,
                'academic_year_id' => $this->academic_year_id,
                'program_cycle_id' => $this->program_cycle_id ?: null,
                'visit_date' => $this->visit_date,
                'visit_type' => $this->visit_type,
                'objective' => $this->objective,
                'notes' => $this->notes,
                'recommendations' => $this->recommendations,
            ]);

            $this->reset();
            $this->dispatch('reloadVisits');
            $this->dispatch('showSuccessAlert', message: 'تم إضافة الزيارة بنجاح');
        } catch (\Exception $e) {
            $this->dispatch('showErrorAlert', message: 'حدث خطأ: ' . $e->getMessage());
        }
    }
}
