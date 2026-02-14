<?php

namespace App\Livewire\Visits;

use App\Models\Visit;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class VisitView extends Component
{
    public $visitId = '';
    public $visit;

    public function render()
    {
        return view('livewire.visits.visit-view');
    }

    #[On('openViewModal')]
    public function loadVisit($visit_id)
    {
        $this->visitId = $visit_id;
        $this->visit = Visit::with(['user', 'school', 'academicYear', 'programCycle', 'attachments'])->find($this->visitId);
        Flux::modal('view-visit')->show();
    }
}
