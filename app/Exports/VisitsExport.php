<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class VisitsExport
{
    public function export($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setRightToLeft(true);
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'Visits Report');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD3D3D3');

        $headers = [
            '#',
            'Visit Date',
            'School',
            'User',
            'Visit Type',
            'Objective',
            'Academic Year',
            'Program Cycle',
        ];

        $columnIndex = 'A';
        foreach ($headers as $header) {
            $cell = $columnIndex . '2';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE8E8E8');
            $columnIndex++;
        }

        $rowIndex = 3;
        foreach ($data as $index => $visit) {
            $programCycle = '';
            if ($visit->programCycle && $visit->programCycle->program) {
                $programCycle = $visit->programCycle->program->name . ' - ' . $visit->programCycle->term;
            }
            $visitDate = $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d') : '';

            $sheet->setCellValue('A' . $rowIndex, $index + 1);
            $sheet->setCellValue('B' . $rowIndex, $visitDate);
            $sheet->setCellValue('C' . $rowIndex, optional($visit->school)->name ?? '');
            $sheet->setCellValue('D' . $rowIndex, optional($visit->user)->name ?? '');
            $sheet->setCellValue('E' . $rowIndex, $visit->visit_type);
            $sheet->setCellValue('F' . $rowIndex, $visit->objective ?? '');
            $sheet->setCellValue('G' . $rowIndex, optional($visit->academicYear)->name ?? '');
            $sheet->setCellValue('H' . $rowIndex, $programCycle);

            $sheet->getStyle('A' . $rowIndex . ':H' . $rowIndex)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $rowIndex++;
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'visits_' . now()->format('Ymd_His') . '.xlsx';
        $filePath = public_path($fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $fileName;
    }
}
