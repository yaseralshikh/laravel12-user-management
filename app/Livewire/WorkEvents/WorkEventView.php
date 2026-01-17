<?php

namespace App\Livewire\WorkEvents;

use App\Models\WorkEvent;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkEventView extends Component
{
    public $workEvent;

    #[On('loadViewModal')]
    public function loadWorkEvent($workEvent)
    {
        $this->workEvent = $workEvent;
    }

    public function render()
    {
        return view('livewire.work-events.work-event-view');
    }
}
