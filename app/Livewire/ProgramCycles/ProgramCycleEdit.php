<?php

namespace App\Livewire\ProgramCycles;

use App\Models\Program;
use App\Models\AcademicYear;
use App\Models\ProgramCycle;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class ProgramCycleEdit extends Component
{
    public $programCycleId;
    public $program_id;
    public $academic_year_id;
    public $term;
    public $status = 'in_progress';
    public $start_date;
    public $end_date;
    public $notes;

    public function rules()
    {
        return [
            'program_id' => ['required', 'exists:programs,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'term' => ['nullable', 'integer', 'min:1', 'max:2'],
            'status' => ['required', 'in:in_progress,completed,suspended,canceled'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected $messages = [
        'program_id.required' => 'اختيار البرنامج مطلوب.',
        'program_id.exists' => 'البرنامج المختار غير موجود.',
        'academic_year_id.required' => 'اختيار السنة الأكاديمية مطلوب.',
        'academic_year_id.exists' => 'السنة الأكاديمية المختارة غير موجودة.',
        'term.integer' => 'الفصل يجب أن يكون رقم.',
        'term.min' => 'الفصل يجب أن يكون 1 أو 2.',
        'term.max' => 'الفصل يجب أن يكون 1 أو 2.',
        'status.required' => 'حالة الدورة مطلوبة.',
        'status.in' => 'حالة الدورة غير صحيحة.',
        'start_date.date' => 'تاريخ البداية يجب أن يكون تاريخ صحيح.',
        'end_date.date' => 'تاريخ النهاية يجب أن يكون تاريخ صحيح.',
        'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو مساوي تاريخ البداية.',
        'notes.string' => 'الملاحظات يجب أن تكون نص.',
    ];

    #[On('openEditModal')]
    public function openEditModal($programCycle)
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $programCycle = $programCycle['programCycle'];

        $this->program_id = $programCycle['program_id'];
        $this->academic_year_id = $programCycle['academic_year_id'];
        $this->term = $programCycle['term'];
        $this->status = $programCycle['status'];
        $this->start_date = $programCycle['start_date'];
        $this->end_date = $programCycle['end_date'];
        $this->notes = $programCycle['notes'] ?? null;
        $this->programCycleId = $programCycle['id'];
        Flux::modal('edit-program-cycle')->show();
    }

    public function updateProgramCycle()
    {
        $this->validate();

        $programCycle = ProgramCycle::find($this->programCycleId);
        if ($programCycle) {
            $programCycle->update([
                'program_id' => $this->program_id,
                'academic_year_id' => $this->academic_year_id,
                'term' => $this->term,
                'status' => $this->status,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'notes' => $this->notes,
            ]);

            $this->dispatch('reloadProgramCycles');
            $this->dispatch('showSuccessAlert', message: 'تم تحديث البيانات بنجاح');
            Flux::modal('edit-program-cycle')->close();
        } else {
            $this->dispatch('showErrorAlert', message: 'دورة البرنامج غير موجودة.');
        }
    }

    public function render()
    {
        return view('livewire.program-cycles.program-cycle-edit', [
            'programs' => Program::where('status', 'active')->get(),
            'academicYears' => AcademicYear::where('status', 'active')->get(),
        ]);
    }
}
