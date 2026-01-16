<?php

namespace App\Livewire\Programs;

use App\Models\Program;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class ProgramEdit extends Component
{
    public $programId;
    public $name;
    public $description;
    public $status = 'active';

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:programs,name,' . $this->programId],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    protected $messages = [
        'name.required' => 'اسم البرنامج مطلوب.',
        'name.max' => 'اسم البرنامج يجب أن لا يتجاوز 255 حرف.',
        'name.string' => 'اسم البرنامج يجب أن يكون نص.',
        'name.unique' => 'اسم البرنامج موجود بالفعل.',
        'description.string' => 'وصف البرنامج يجب أن يكون نص.',
        'status.required' => 'حالة البرنامج مطلوبة.',
        'status.in' => 'حالة البرنامج يجب أن تكون نشط أو غير نشط.',
    ];

    #[On('openEditModal')]
    public function openEditModal($program)
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $program = $program['program'];

        $this->name = $program['name'];
        $this->description = $program['description'] ?? null;
        $this->status = $program['status'];
        $this->programId = $program['id'];
        Flux::modal('edit-program')->show();
    }

    public function updateProgram()
    {
        $this->validate();

        $program = Program::find($this->programId);
        if ($program) {
            $program->update([
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status,
            ]);

            $this->dispatch('reloadPrograms');
            $this->dispatch('showSuccessAlert', message: 'تم تحديث البيانات بنجاح');
            Flux::modal('edit-program')->close();
        } else {
            $this->dispatch('showErrorAlert', message: 'البرنامج غير موجود.');
        }
    }

    public function render()
    {
        return view('livewire.programs.program-edit');
    }
}
