<?php

namespace App\Livewire\Programs;

use App\Models\Program;
use Flux\Flux;
use Livewire\Component;

class ProgramCreate extends Component
{
    public $name;
    public $description;
    public $status = 'active';

    protected $rules = [
        'name' => ['required', 'string', 'max:255', 'unique:programs'],
        'description' => ['nullable', 'string'],
        'status' => ['required', 'in:active,inactive'],
    ];

    protected $messages = [
        'name.required' => 'اسم البرنامج مطلوب.',
        'name.unique' => 'اسم البرنامج موجود بالفعل.',
        'name.max' => 'اسم البرنامج يجب أن لا يتجاوز 255 حرف.',
        'name.string' => 'اسم البرنامج يجب أن يكون نص.',
        'description.string' => 'وصف البرنامج يجب أن يكون نص.',
        'status.required' => 'حالة البرنامج مطلوبة.',
        'status.in' => 'حالة البرنامج يجب أن تكون نشط أو غير نشط.',
    ];

    public function submit()
    {
        $validatedData = $this->validate();
        Program::create($validatedData);

        $this->reset();
        $this->dispatch('reloadPrograms');
        $this->dispatch('showSuccessAlert', message: 'تم حفظ البيانات بنجاح');
        Flux::modal('create-program')->close();
    }

    public function render()
    {
        return view('livewire.programs.program-create');
    }
}
