<?php

namespace App\Livewire\Sectors;

use App\Models\Sector;
use Flux\Flux;
use Livewire\Component;

class SectorCreate extends Component
{
    public $name;
    public $description;
    public $status = 'active';

    protected $rules = [
        'name' => ['required', 'string', 'max:255', 'unique:sectors'],
        'description' => ['nullable', 'string'],
        'status' => ['required', 'in:active,inactive'],
    ];

    protected $messages = [
        'name.required' => 'اسم القطاع مطلوب.',
        'name.unique' => 'اسم القطاع موجود بالفعل.',
        'name.max' => 'اسم القطاع يجب أن لا يتجاوز 255 حرف.',
        'name.string' => 'اسم القطاع يجب أن يكون نص.',
        'description.string' => 'وصف القطاع يجب أن يكون نص.',
        'status.required' => 'حالة القطاع مطلوبة.',
        'status.in' => 'حالة القطاع يجب أن تكون نشط أو غير نشط.',
    ];

    public function submit()
    {
        $validatedData = $this->validate();
        Sector::create($validatedData);

        $this->reset();
        $this->dispatch('reloadSectors');
        $this->dispatch('showSuccessAlert', message: 'تم حفظ البيانات بنجاح');
        Flux::modal('create-sector')->close();
    }

    public function render()
    {
        return view('livewire.sectors.sector-create');
    }
}
