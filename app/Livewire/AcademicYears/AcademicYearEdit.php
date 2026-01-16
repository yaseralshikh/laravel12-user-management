<?php

namespace App\Livewire\AcademicYears;

use App\Models\AcademicYear;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class AcademicYearEdit extends Component
{
    public $academicYearId;
    public $name;
    public $starts_on;
    public $ends_on;
    public $status = 'active';

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:academic_years,name,' . $this->academicYearId],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['required', 'date', 'after:starts_on'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    protected $messages = [
        'name.required' => 'اسم السنة الأكاديمية مطلوب.',
        'name.max' => 'اسم السنة الأكاديمية يجب أن لا يتجاوز 255 حرف.',
        'name.string' => 'اسم السنة الأكاديمية يجب أن يكون نص.',
        'name.unique' => 'اسم السنة الأكاديمية موجود بالفعل.',
        'starts_on.required' => 'تاريخ البداية مطلوب.',
        'starts_on.date' => 'تاريخ البداية يجب أن يكون تاريخ صحيح.',
        'ends_on.required' => 'تاريخ النهاية مطلوب.',
        'ends_on.date' => 'تاريخ النهاية يجب أن يكون تاريخ صحيح.',
        'ends_on.after' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية.',
        'status.required' => 'حالة السنة الأكاديمية مطلوبة.',
        'status.in' => 'حالة السنة الأكاديمية يجب أن تكون نشط أو غير نشط.',
    ];

    #[On('openEditModal')]
    public function openEditModal($academicYear)
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $academicYear = $academicYear['academicYear'];

        $this->name = $academicYear['name'];
        $this->starts_on = $academicYear['starts_on'];
        $this->ends_on = $academicYear['ends_on'];
        $this->status = $academicYear['status'];
        $this->academicYearId = $academicYear['id'];
        Flux::modal('edit-academic-year')->show();
    }

    public function updateAcademicYear()
    {
        $this->validate();

        $academicYear = AcademicYear::find($this->academicYearId);
        if ($academicYear) {
            $academicYear->update([
                'name' => $this->name,
                'starts_on' => $this->starts_on,
                'ends_on' => $this->ends_on,
                'status' => $this->status,
            ]);

            $this->dispatch('reloadAcademicYears');
            $this->dispatch('showSuccessAlert', message: 'تم تحديث البيانات بنجاح');
            Flux::modal('edit-academic-year')->close();
        } else {
            $this->dispatch('showErrorAlert', message: 'السنة الأكاديمية غير موجودة.');
        }
    }

    public function render()
    {
        return view('livewire.academic-years.academic-year-edit');
    }
}
