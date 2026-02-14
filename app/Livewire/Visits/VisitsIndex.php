<?php

namespace App\Livewire\Visits;

use App\Models\Visit;
use App\Models\School;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\ProgramCycle;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class VisitsIndex extends Component
{
    use WithPagination;

    public $visitId;
    public $term = '';
    public string $sortField = 'visit_date';
    public string $sortDirection = 'desc';
    public $visitTypeFilter = '';
    public $schoolFilter = '';
    public $userFilter = '';
    public $academicYearFilter = '';
    public $programCycleFilter = '';
    public $dateFromFilter = '';
    public $dateToFilter = '';
    public $statusFilter = '';

    public function updatedTerm()
    {
        $this->resetPage();
    }

    public function updatedVisitTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedSchoolFilter()
    {
        $this->resetPage();
    }

    public function updatedUserFilter()
    {
        $this->resetPage();
    }

    public function updatedAcademicYearFilter()
    {
        $this->resetPage();
    }

    public function updatedProgramCycleFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFromFilter()
    {
        $this->resetPage();
    }

    public function updatedDateToFilter()
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

    #[On('reloadVisits')]
    public function reloadPage()
    {
        $this->resetPage();
    }

    public function view($visitId)
    {
        if (Visit::with(['user', 'school', 'academicYear', 'programCycle', 'attachments'])->find($visitId)) {
            $this->dispatch('openViewModal', visit_id: $visitId);
        }
    }

    public function edit($visitId)
    {
        if (Visit::with(['user', 'school', 'academicYear', 'programCycle'])->find($visitId)) {
            $this->dispatch('openEditModal', visit_id: $visitId);
        }
    }

    public function delete($visitId)
    {
        $this->visitId = $visitId;
        Flux::modal('delete-visit')->show();
    }

    public function destroy()
    {
        $visit = Visit::findOrFail($this->visitId);

        // حذف المرفقات أولاً
        $visit->attachments()->delete();

        // ثم حذف الزيارة
        $visit->delete();

        Flux::modal('delete-visit')->close();
        $this->dispatch('showSuccessAlert', message: 'تم حذف الزيارة بنجاح');
        $this->resetPage();
    }

    public function exportExcel()
    {
        $data = $this->applyFilters(Visit::query())
            ->with(['user', 'school', 'academicYear'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        // يمكن إضافة ExportClass مخصصة لاحقاً
        $this->dispatch('showSuccessAlert', message: 'جاري تحضير الملف...');
    }

    public function exportPdf()
    {
        $data = $this->applyFilters(Visit::query())
            ->with(['user', 'school', 'academicYear', 'programCycle'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        $html = view('exports.visits', compact('data'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
        ]);

        $mpdf->WriteHTML($html);

        $fileName = 'visits_' . now()->format('Ymd_His') . '.pdf';
        $filePath = public_path($fileName);
        $mpdf->Output($filePath, Destination::FILE);

        $this->dispatch('showSuccessAlert', message: 'تم إنشاء ملف PDF بنجاح!');

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function getVisitsProperty()
    {
        $visits = $this->applyFilters(
            Visit::query()->with(['user', 'school', 'academicYear', 'programCycle'])
        )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return $visits;
    }

    public function render()
    {
        return view('livewire.visits.visits-index', [
            'visits' => $this->visits,
            'visitTypes' => [
                'فنية' => 'فنية',
                'إدارية' => 'إدارية',
                'تقويمية' => 'تقويمية',
                'متابعة' => 'متابعة',
                'أخرى' => 'أخرى'
            ],
            'schools' => School::where('status', 'نشط')->orderBy('name')->get(),
            'users' => User::where('status', 'active')->orderBy('name')->get(),
            'academicYears' => AcademicYear::where('status', 'active')->orderBy('name')->get(),
            'programCycles' => ProgramCycle::whereIn('status', ['active', 'in_progress'])->with('program')->orderBy('term')->get(),
        ]);
    }

    private function applyFilters($query)
    {
        return $query
            ->when($this->term, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('objective', 'like', '%' . $this->term . '%')
                        ->orWhere('notes', 'like', '%' . $this->term . '%')
                        ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $this->term . '%'))
                        ->orWhereHas('school', fn($s) => $s->where('name', 'like', '%' . $this->term . '%'));
                });
            })
            ->when($this->visitTypeFilter, fn($q) => $q->where('visit_type', $this->visitTypeFilter))
            ->when($this->schoolFilter, fn($q) => $q->where('school_id', $this->schoolFilter))
            ->when($this->userFilter, fn($q) => $q->where('user_id', $this->userFilter))
            ->when($this->academicYearFilter, fn($q) => $q->where('academic_year_id', $this->academicYearFilter))
            ->when($this->programCycleFilter, fn($q) => $q->where('program_cycle_id', $this->programCycleFilter))
            ->when($this->dateFromFilter, fn($q) => $q->where('visit_date', '>=', $this->dateFromFilter))
            ->when($this->dateToFilter, fn($q) => $q->where('visit_date', '<=', $this->dateToFilter));
    }
}
