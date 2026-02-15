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
        $this->visit = Visit::with(['user', 'school', 'academicYear', 'programCycle.program', 'attachments'])->find($this->visitId);
        Flux::modal('view-visit')->show();
    }

    /**
     * Download visit report as PDF.
     */
    public function downloadPdf()
    {
        if (!$this->visit) {
            return;
        }

        // Load relationships if not already loaded
        $this->visit->load(['user', 'school', 'academicYear', 'programCycle.program']);

        // Configure mPDF with RTL support for Arabic
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 15,
            'default_font' => 'dejavusans'
        ]);

        // Generate PDF content
        $html = view('pdf.visit-report', ['visit' => $this->visit])->render();

        $mpdf->WriteHTML($html);

        // Generate filename
        $filename = 'visit-report-' . $this->visit->id . '-' . now()->format('Y-m-d') . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $filename, ['Content-Type' => 'application/pdf']);
    }
}
