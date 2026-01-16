<?php

namespace App\Livewire\AcademicYears;

use App\Models\AcademicYear;
use Flux\Flux;
use Livewire\Component;

class AcademicYearCreate extends Component
{
    public $name;
    public $starts_on;
    public $ends_on;
    public $status = 'active';

    protected $rules = [
        'name' => ['required', 'string', 'max:255', 'unique:academic_years'],
        'starts_on' => ['required', 'date'],
        'ends_on' => ['required', 'date', 'after:starts_on'],
        'status' => ['required', 'in:active,inactive'],
    ];

    protected $messages = [
        'name.required' => 'اسم السنة الأكاديمية مطلوب.',
        'name.unique' => 'اسم السنة الأكاديمية موجود بالفعل.',
        'name.max' => 'اسم السنة الأكاديمية يجب أن لا يتجاوز 255 حرف.',
        'name.string' => 'اسم السنة الأكاديمية يجب أن يكون نص.',
        'starts_on.required' => 'تاريخ البداية مطلوب.',
        'starts_on.date' => 'تاريخ البداية يجب أن يكون تاريخ صحيح.',
        'ends_on.required' => 'تاريخ النهاية مطلوب.',
        'ends_on.date' => 'تاريخ النهاية يجب أن يكون تاريخ صحيح.',
        'ends_on.after' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية.',
        'status.required' => 'حالة السنة الأكاديمية مطلوبة.',
        'status.in' => 'حالة السنة الأكاديمية يجب أن تكون نشط أو غير نشط.',
    ];

    public function submit()
    {
        $validatedData = $this->validate();
        AcademicYear::create($validatedData);

        $this->reset();
        $this->dispatch('reloadAcademicYears');
        $this->dispatch('showSuccessAlert', message: 'تم حفظ البيانات بنجاح');
        Flux::modal('create-academic-year')->close();
    }

    public function render()
    {
        return view('livewire.academic-years.academic-year-create');
    }
}
