<?php

namespace App\Livewire\Schools;

use \Mpdf\Output\Destination;
use App\Exports\SchoolsExport;
use App\Models\School;
use App\Models\User;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;

class SchoolsIndex extends Component
{
    use WithPagination;

    public $schoolId;
    public $term = '';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';
    public $genderFilter = '';
    public $statusFilter = '';
    public $schoolTypeFilter = '';
    public $buildingTypeFilter = '';
    public $sectorFilter = '';
    public $stageFilter = '';
    public $complexFilter = '';

    public function updatedTerm()
    {
        $this->resetPage();
    }

    public function updatedGenderFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSchoolTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedBuildingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedSectorFilter()
    {
        $this->resetPage();
    }

    public function updatedStageFilter()
    {
        $this->resetPage();
    }

    public function updatedComplexFilter()
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

    #[On('reloadSchools')]
    public function reloadPage()
    {
        $this->resetPage();
    }

    public function edit($schoolId)
    {
        if ($school = School::with(['coordinator', 'principal'])->find($schoolId)) {
            $this->dispatch('openEditModal', ['school' => $school]);
        }
    }

    public function delete($schoolId)
    {
        $this->schoolId = $schoolId;
        Flux::modal('delete-school')->show();
    }

    public function destroy()
    {
        $school = School::findOrFail($this->schoolId);
        $school->delete();

        Flux::modal('delete-school')->close();
        $this->dispatch('showSuccessAlert', message: 'تم حذف المدرسة بنجاح');
        $this->resetPage();
    }

    public function exportExcel()
    {
        $data = $this->applyFilters(School::query())
            ->orderBy($this->sortField, $this->sortDirection)
            ->latest('created_at')
            ->get();

        $export = new SchoolsExport();
        $file = $export->export($data);
        $this->dispatch('showSuccessAlert', message: 'تم إنشاء الملف بنجاح!');

        return response()->download(public_path($file))->deleteFileAfterSend(true);
    }

    public function exportPdf()
    {
        $data = $this->applyFilters(School::query())
            ->orderBy($this->sortField, $this->sortDirection)
            ->latest('created_at')
            ->get();

        $html = view('exports.schools', compact('data'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
        ]);

        $mpdf->WriteHTML($html);

        $fileName = 'schools_' . now()->format('Ymd_His') . '.pdf';
        $filePath = public_path($fileName);
        $mpdf->Output($filePath, Destination::FILE);

        $this->dispatch('showSuccessAlert', message: 'تم إنشاء ملف PDF بنجاح!');

        return response()->download($filePath)->deleteFileAfterSend(true);
    }      
        
    public function getSchoolsProperty()
    {
        $schools = $this->applyFilters(
            School::query()->with(['coordinator', 'principal'])
        )
        ->orderBy($this->sortField, $this->sortDirection)
        ->latest('created_at')
        ->paginate(10);

        return $schools;
    }

    public function render()
    {
        return view('livewire.schools.schools-index', [
            'schools' => $this->schools,
            'genders' => config('schools.genders'),
            'statuses' => config('schools.statuses'),
            'schoolTypes' => config('schools.school_types'),
            'buildingTypes' => config('schools.building_types'),
            'educationalSectors' => config('schools.educational_sectors'),
            'stageOptions' => config('schools.stages'),
        ]);
    }

    private function applyFilters($query)
    {
        return $query
            ->when($this->term, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->term . '%')
                        ->orWhere('ministry_code', 'like', '%' . $this->term . '%');
                });
            })
            ->when($this->genderFilter, fn($q) => $q->where('gender', $this->genderFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->schoolTypeFilter, fn($q) => $q->where('school_type', $this->schoolTypeFilter))
            ->when($this->buildingTypeFilter, fn($q) => $q->where('building_type', $this->buildingTypeFilter))
            ->when($this->sectorFilter, fn($q) => $q->where('educational_sector', $this->sectorFilter))
            ->when($this->stageFilter, fn($q) => $q->where('stage', $this->stageFilter))
            ->when($this->complexFilter !== '', fn($q) => $q->where('is_complex', $this->complexFilter === '1'));
    }
}
