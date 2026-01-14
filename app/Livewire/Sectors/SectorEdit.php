<?php

namespace App\Livewire\Sectors;

use App\Models\Sector;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class SectorEdit extends Component
{
    public $sectorId;
    public $name;
    public $description;
    public $status = 'active';

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:sectors,name,' . $this->sectorId],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    protected $messages = [
        'name.required' => 'اسم القطاع مطلوب.',
        'name.max' => 'اسم القطاع يجب أن لا يتجاوز 255 حرف.',
        'name.string' => 'اسم القطاع يجب أن يكون نص.',
        'name.unique' => 'اسم القطاع موجود بالفعل.',
        'description.string' => 'وصف القطاع يجب أن يكون نص.',
        'status.required' => 'حالة القطاع مطلوبة.',
        'status.in' => 'حالة القطاع يجب أن تكون نشط أو غير نشط.',
    ];

    #[On('openEditModal')]
    public function openEditModal($sector)
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $sector = $sector['sector'];

        $this->name = $sector['name'];
        $this->description = $sector['description'] ?? null;
        $this->status = $sector['status'];
        $this->sectorId = $sector['id'];
        Flux::modal('edit-sector')->show();
    }

    public function updateSector()
    {
        $this->validate();

        $sector = Sector::find($this->sectorId);
        if ($sector) {
            $sector->update([
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status,
            ]);

            $this->dispatch('reloadSectors');
            $this->dispatch('showSuccessAlert', message: 'تم تحديث البيانات بنجاح');
            Flux::modal('edit-sector')->close();
        } else {
            $this->dispatch('showErrorAlert', message: 'القطاع غير موجود.');
        }
    }

    public function render()
    {
        return view('livewire.sectors.sector-edit');
    }
}
