<?php

namespace App\Livewire\WorkEvents;

use App\Models\WorkEvent;
use App\Models\AcademicYear;
use App\Models\ProgramCycle;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class WorkEventCreate extends Component
{
    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('required|date')]
    public $event_date = '';

    #[Validate('required|string')]
    public $event_type = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('nullable|string|max:255')]
    public $location = '';

    #[Validate('nullable|date_format:H:i')]
    public $starts_at = '';

    #[Validate('nullable|date_format:H:i')]
    public $ends_at = '';

    #[Validate('required|exists:users,id')]
    public $user_id = '';

    #[Validate('required|exists:academic_years,id')]
    public $academic_year_id = '';

    #[Validate('nullable|exists:program_cycles,id')]
    public $program_cycle_id = '';

    #[On('resetCreateForm')]
    public function resetForm()
    {
        $this->reset(['title', 'event_date', 'event_type', 'description', 'location', 'starts_at', 'ends_at', 'user_id', 'academic_year_id', 'program_cycle_id']);
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        WorkEvent::create($this->only([
            'title',
            'event_date',
            'event_type',
            'description',
            'location',
            'starts_at',
            'ends_at',
            'user_id',
            'academic_year_id',
            'program_cycle_id'
        ]));

        $this->dispatch('reloadWorkEvents');
        $this->dispatch('showSuccessAlert', message: 'تم إضافة الحدث بنجاح');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.work-events.work-event-create', [
            'eventTypes' => [
                'لقاء علمي' => 'لقاء علمي',
                'ورشة عمل' => 'ورشة عمل',
                'انتداب' => 'انتداب',
                'تدريب' => 'تدريب',
                'ندوة' => 'ندوة',
                'مؤتمر' => 'مؤتمر',
                'أخرى' => 'أخرى'
            ],
            'users' => User::where('status', 'active')->orderBy('name')->get(),
            'academicYears' => AcademicYear::where('status', 'active')->orderBy('name')->get(),
            'programCycles' => ProgramCycle::where('status', 'active')->with('program')->orderBy('term')->get(),
        ]);
    }
}
