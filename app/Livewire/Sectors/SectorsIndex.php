<?php

namespace App\Livewire\Sectors;

use App\Models\Sector;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class SectorsIndex extends Component
{
    use WithPagination;

    public $sectorId;
    public $term = '';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public function updatedTerm()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    #[On('reloadSectors')]
    public function reloadPage()
    {
        $this->resetPage();
    }

    public function edit($sectorId)
    {
        if ($sector = Sector::find($sectorId)) {
            $this->dispatch('openEditModal', ['sector' => $sector]);
        }
    }

    public function delete($sectorId)
    {
        $this->sectorId = $sectorId;
        Flux::modal('delete-sector')->show();
    }

    public function destroy()
    {
        $sector = Sector::findOrFail($this->sectorId);
        $sector->delete();

        Flux::modal('delete-sector')->close();
        $this->dispatch('showSuccessAlert', message: 'تم حذف القطاع بنجاح');
        $this->resetPage();
    }

    public function render()
    {
        $sectors = Sector::query()
            ->when(
                $this->term,
                fn($q) =>
                $q->where('name', 'like', '%' . $this->term . '%')
                    ->orWhere('description', 'like', '%' . $this->term . '%')
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->latest('created_at')
            ->paginate(10);

        return view('livewire.sectors.sectors-index', compact('sectors'));
    }
}
