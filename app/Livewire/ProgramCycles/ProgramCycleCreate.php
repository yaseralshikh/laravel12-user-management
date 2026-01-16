<?php

namespace App\Livewire\ProgramCycles;

use App\Models\Program;
use App\Models\AcademicYear;
use App\Models\ProgramCycle;
use Flux\Flux;
use Livewire\Component;

class ProgramCycleCreate extends Component
{
    public $program_id;
    public $academic_year_id;
    public $term;
    public $status = 'in_progress';
    public $start_date;
    public $end_date;
    public $notes;

    protected $rules = [
        'program_id' => ['required', 'exists:programs,id'],
        'academic_year_id' => ['required', 'exists:academic_years,id'],
        'term' => ['nullable', 'integer', 'min:1', 'max:2'],
        'status' => ['required', 'in:in_progress,completed,suspended,canceled'],
        'start_date' => ['nullable', 'date'],
        'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        'notes' => ['nullable', 'string'],
    ];

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

    public function submit()
    {
        $validatedData = $this->validate();
        ProgramCycle::create($validatedData);

        $this->reset();
        $this->dispatch('reloadProgramCycles');
        $this->dispatch('showSuccessAlert', message: 'تم حفظ البيانات بنجاح');
        Flux::modal('create-program-cycle')->close();
    }

    public function render()
    {
        return view('livewire.program-cycles.program-cycle-create', [
            'programs' => Program::where('status', 'active')->get(),
            'academicYears' => AcademicYear::where('status', 'active')->get(),
        ]);
    }
}
