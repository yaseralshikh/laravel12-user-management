<?php

namespace App\Livewire\Visits;

use App\Models\Visit;
use App\Models\School;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\ProgramCycle;
use App\Models\Sector;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class VisitEdit extends Component
{
    public $visitId = '';

    #[Validate('nullable|exists:sectors,id')]
    public $sector_id = '';

    #[Validate('nullable|string')]
    public $stage = '';

    #[Validate('required|exists:schools,id')]
    public $school_id = '';

    #[Validate('required|exists:users,id')]
    public $user_id = '';

    #[Validate('required|exists:academic_years,id')]
    public $academic_year_id = '';

    #[Validate('nullable|exists:program_cycles,id')]
    public $program_cycle_id = '';

    #[Validate('required|date')]
    public $visit_date = '';

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
        // الحصول على المدارس المفلترة بناءً على القطاع والمرحلة
        // إذا تم اختيار القطاع والمرحلة، قم بالتصفية، وإلا أظهر جميع المدارس
        $query = School::where('status', 'نشط');

        if ($this->sector_id && $this->stage) {
            $query = $query->where('sector_id', $this->sector_id)
                ->where('stage', $this->stage);
        }

        return view('livewire.visits.visit_edit', [
            'sectors' => Sector::where('status', 'active')->orderBy('name')->get(),
            'stages' => School::where('status', 'نشط')
                ->distinct()
                ->pluck('stage')
                ->filter()
                ->sort()
                ->values(),
            'schools' => $query->orderBy('name')->get(),
            'users' => User::where('status', 'active')->orderBy('name')->get(),
            'academicYears' => AcademicYear::where('status', 'active')->orderBy('name')->get(),
            'programCycles' => ProgramCycle::whereIn('status', ['active', 'in_progress'])->with('program')->orderBy('term')->get(),
            'programs' => \App\Models\Program::where('status', 'active')->orderBy('name')->get(),
            'visitTypes' => [
                'فنية' => 'فنية',
                'إدارية' => 'إدارية',
                'تقويمية' => 'تقويمية',
                'متابعة' => 'متابعة',
                'أخرى' => 'أخرى'
            ],
        ]);
    }

    #[On('updateSchools')]
    public function updateSchools()
    {
        // إعادة تعيين حقل المدارس عند تغيير القطاع أو المرحلة
        $this->school_id = '';
    }

    #[On('openEditModal')]
    public function loadVisit($visit_id)
    {
        $visit = Visit::find($visit_id);

        if (!$visit) {
            return;
        }

        $this->visitId = $visit->id;
        $this->school_id = $visit->school_id;

        // الحصول على المدرسة لاستخراج القطاع والمرحلة
        $school = School::find($visit->school_id);
        if ($school) {
            $this->sector_id = $school->sector_id;
            $this->stage = $school->stage;
        }

        $this->user_id = $visit->user_id;
        $this->academic_year_id = $visit->academic_year_id;
        $this->program_cycle_id = $visit->program_cycle_id;
        $this->visit_date = $visit->visit_date;
        $this->visit_type = $visit->visit_type;
        $this->objective = $visit->objective ?? '';
        $this->notes = $visit->notes ?? '';
        $this->recommendations = $visit->recommendations ?? '';

        Flux::modal('edit-visit')->show();
    }

    public function save()
    {
        $this->validate();

        try {
            $visit = Visit::findOrFail($this->visitId);

            $visit->update([
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

            Flux::modal('edit-visit')->close();
            $this->reset();
            $this->dispatch('reloadVisits');
            $this->dispatch('showSuccessAlert', message: 'تم تحديث الزيارة بنجاح');
        } catch (\Exception $e) {
            $this->dispatch('showErrorAlert', message: 'حدث خطأ: ' . $e->getMessage());
        }
    }
}
