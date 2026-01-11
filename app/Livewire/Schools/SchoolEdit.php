<?php

namespace App\Livewire\Schools;

use App\Models\School;
use App\Models\User;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class SchoolEdit extends Component
{
    public $schoolId;
    public $name;
    public $ministry_code;
    public $gender;
    public $stage = '';
    public $is_complex = false;
    public $school_type;
    public $building_type;
    public $status;
    public $educational_sector;
    public $coordinator_id = null;
    public $principal_id = null;

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'ministry_code' => ['required', 'string', 'max:50', 'unique:schools,ministry_code,' . $this->schoolId],
            'gender' => ['required', 'in:male,female'],
            'stage' => ['required', 'in:ابتدائي,متوسط,ثانوي'],
            'is_complex' => ['boolean'],
            'school_type' => ['required', 'in:حكومي,أهلي,عالمي'],
            'building_type' => ['required', 'in:حكومي,ملك,مستأجر'],
            'status' => ['required', 'in:active,inactive'],
            'educational_sector' => ['required', 'string', 'max:255'],
            'coordinator_id' => ['nullable', 'exists:users,id'],
            'principal_id' => ['nullable', 'exists:users,id'],
        ];
    }

    protected $messages = [
        'name.required' => 'اسم المدرسة مطلوب',
        'name.max' => 'اسم المدرسة لا يجب أن يتجاوز 255 حرف',
        'ministry_code.required' => 'الرمز الوزاري مطلوب',
        'ministry_code.unique' => 'الرمز الوزاري موجود مسبقاً',
        'gender.required' => 'نوع المدرسة مطلوب',
        'stage.required' => 'المراحل الدراسية مطلوبة',
        'stage.in' => 'القيمة المحددة للمرحلة الدراسية غير صحيحة',
        'school_type.required' => 'نوع التعليم مطلوب',
        'building_type.required' => 'نوع المبنى مطلوب',
        'status.required' => 'حالة المدرسة مطلوبة',
        'educational_sector.required' => 'القطاع التعليمي مطلوب',
        'coordinator_id.exists' => 'المنسق غير موجود',
        'principal_id.exists' => 'المدير غير موجود',
    ];

    #[On('openEditModal')]
    public function openEditModal($school)
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $school = $school['school'];

        $this->schoolId = $school['id'];
        $this->name = $school['name'];
        $this->ministry_code = $school['ministry_code'];
        $this->gender = $school['gender'];
        $this->stage = $school['stage'];
        $this->is_complex = $school['is_complex'];
        $this->school_type = $school['school_type'];
        $this->building_type = $school['building_type'];
        $this->status = $school['status'];
        $this->educational_sector = $school['educational_sector'];
        $this->coordinator_id = $school['coordinator_id'];
        $this->principal_id = $school['principal_id'];

        Flux::modal('edit-school')->show();
    }

    public function updateSchool()
    {
        $this->validate();

        if ($school = School::find($this->schoolId)) {
            $school->update([
                'name' => $this->name,
                'ministry_code' => $this->ministry_code,
                'gender' => $this->gender,
                'stage' => $this->stage,
                'is_complex' => $this->is_complex,
                'school_type' => $this->school_type,
                'building_type' => $this->building_type,
                'status' => $this->status,
                'educational_sector' => $this->educational_sector,
                'coordinator_id' => $this->coordinator_id,
                'principal_id' => $this->principal_id,
            ]);

            $this->dispatch('reloadSchools');
            $this->dispatch('showSuccessAlert', message: 'تم تحديث البيانات بنجاح');
            Flux::modal('edit-school')->close();
        }
    }

    public function render()
    {
        $coordinators = User::whereHas('roles', function($q) {
            $q->where('name', 'coordinator');
        })->get();
        $principals = User::whereHas('roles', function($q) {
            $q->where('name', 'principal');
        })->get();

        $genders = config('schools.genders');
        $schoolTypes = config('schools.school_types');
        $buildingTypes = config('schools.building_types');
        $stageOptions = config('schools.stages');
        $statuses = config('schools.statuses');
        $educationalSectors = config('schools.educational_sectors');

        return view('livewire.schools.school-edit', compact('coordinators', 'principals', 'genders', 'schoolTypes', 'buildingTypes', 'stageOptions', 'statuses', 'educationalSectors'));
    }
}
