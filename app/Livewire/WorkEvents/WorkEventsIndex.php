<?php

namespace App\Livewire\WorkEvents;

use App\Models\WorkEvent;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\ProgramCycle;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class WorkEventsIndex extends Component
{
    use WithPagination;

    public $workEventId;
    public $term = '';
    public string $sortField = 'event_date';
    public string $sortDirection = 'desc';
    public $eventTypeFilter = '';
    public $userFilter = '';
    public $academicYearFilter = '';
    public $programCycleFilter = '';
    public $dateFromFilter = '';
    public $dateToFilter = '';
    public $locationFilter = '';

    public function updatedTerm()
    {
        $this->resetPage();
    }

    public function updatedEventTypeFilter()
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

    public function updatedLocationFilter()
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

    public function openCreateModal()
    {
        $this->dispatch('resetCreateForm');
        Flux::modal('create-work-event')->show();
    }

    #[On('reloadWorkEvents')]
    public function reloadPage()
    {
        $this->resetPage();
    }

    public function view($workEventId)
    {
        if ($workEvent = WorkEvent::with(['user', 'academicYear', 'programCycle'])->find($workEventId)) {
            $this->dispatch('loadViewModal', workEvent: $workEvent->toArray());
            Flux::modal('view-work-event')->show();
        }
    }

    public function edit($workEventId)
    {
        if ($workEvent = WorkEvent::with(['user', 'academicYear', 'programCycle'])->find($workEventId)) {
            $this->dispatch('loadEditModal', workEvent: $workEvent->toArray());
            Flux::modal('edit-work-event')->show();
        }
    }

    public function delete($workEventId)
    {
        $this->workEventId = $workEventId;
        Flux::modal('delete-work-event')->show();
    }

    public function destroy()
    {
        $workEvent = WorkEvent::findOrFail($this->workEventId);
        $workEvent->delete();

        Flux::modal('delete-work-event')->close();
        $this->dispatch('showSuccessAlert', message: 'تم حذف الحدث بنجاح');
        $this->resetPage();
    }

    public function exportExcel()
    {
        $data = $this->applyFilters(WorkEvent::query())
            ->with(['user', 'academicYear', 'programCycle'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        // يمكن إضافة ExportClass مخصصة لاحقاً
        $this->dispatch('showSuccessAlert', message: 'جاري تحضير الملف...');
    }

    public function exportPdf()
    {
        $data = $this->applyFilters(WorkEvent::query())
            ->with(['user', 'academicYear', 'programCycle'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        $html = view('exports.work-events', compact('data'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
        ]);

        $mpdf->WriteHTML($html);

        $fileName = 'work_events_' . now()->format('Ymd_His') . '.pdf';
        $filePath = public_path($fileName);
        $mpdf->Output($filePath, Destination::FILE);

        $this->dispatch('showSuccessAlert', message: 'تم إنشاء ملف PDF بنجاح!');

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function getWorkEventsProperty()
    {
        $workEvents = $this->applyFilters(
            WorkEvent::query()->with(['user', 'academicYear', 'programCycle'])
        )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return $workEvents;
    }

    public function render()
    {
        return view('livewire.work-events.work-events-index', [
            'workEvents' => $this->workEvents,
            'eventTypes' => [
                'لقاء علمي' => 'لقاء علمي',
                'ورشة عمل' => 'ورشة عمل',
                'انتداب' => 'انتداب',
                'تدريب' => 'تدريب',
                'ندوة' => 'ندوة',
                'مؤتمر' => 'مؤتمر',
                'أخرى' => 'أخرى'
            ],
            'users' => User::where('status', 'active')->orderBy('name')->get(),
            'academicYears' => AcademicYear::where('status', 'active')->orderBy('name')->get(),
            'programCycles' => ProgramCycle::where('status', 'active')->with('program')->orderBy('term')->get(),
        ]);
    }

    private function applyFilters($query)
    {
        return $query
            ->when($this->term, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('title', 'like', '%' . $this->term . '%')
                        ->orWhere('description', 'like', '%' . $this->term . '%')
                        ->orWhere('location', 'like', '%' . $this->term . '%')
                        ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $this->term . '%'));
                });
            })
            ->when($this->eventTypeFilter, fn($q) => $q->where('event_type', $this->eventTypeFilter))
            ->when($this->userFilter, fn($q) => $q->where('user_id', $this->userFilter))
            ->when($this->academicYearFilter, fn($q) => $q->where('academic_year_id', $this->academicYearFilter))
            ->when($this->programCycleFilter, fn($q) => $q->where('program_cycle_id', $this->programCycleFilter))
            ->when($this->locationFilter, fn($q) => $q->where('location', 'like', '%' . $this->locationFilter . '%'))
            ->when($this->dateFromFilter, fn($q) => $q->where('event_date', '>=', $this->dateFromFilter))
            ->when($this->dateToFilter, fn($q) => $q->where('event_date', '<=', $this->dateToFilter));
    }
}
