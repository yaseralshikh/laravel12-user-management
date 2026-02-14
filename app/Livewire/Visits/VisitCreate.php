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

class VisitCreate extends Component
{
    #[Validate('nullable|exists:sectors,id')]
    public $sector_id = '';

    #[Validate('nullable|string')]
    public $stage = '';

    #[Validate('required|exists:schools,id')]
    public $school_id = '';

    #[Validate('required|exists:users,id')]
    public $user_id = '';

    #[Validate('nullable|exists:academic_years,id')]
    public $academic_year_id = '';

    #[Validate('nullable|array|exists:program_cycles,id')]
    public array $program_cycle_ids = [];

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

        return view('livewire.visits.visit_create', [
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
            $visit = Visit::create([
                'school_id' => $this->school_id,
                'user_id' => $this->user_id,
                'academic_year_id' => $this->academic_year_id,
                'program_cycle_id' => null,
                'visit_date' => $this->visit_date,
                'visit_type' => $this->visit_type,
                'objective' => $this->objective,
                'notes' => $this->notes,
                'recommendations' => $this->recommendations,
            ]);

            $visit->programCycles()->sync($this->program_cycle_ids);

            Flux::modal('create-visit')->close();
            $this->reset();
            $this->dispatch('reloadVisits');
            $this->dispatch('showSuccessAlert', message: 'تم إضافة الزيارة بنجاح');
        } catch (\Exception $e) {
            $this->dispatch('showErrorAlert', message: 'حدث خطأ: ' . $e->getMessage());
        }
    }
}
