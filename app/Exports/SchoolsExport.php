<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SchoolsExport
{
    public function export($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // تعيين الاتجاه من اليمين لليسار
        $sheet->setRightToLeft(true);

        // إضافة العنوان في السطر الأول
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'تقرير المدارس');
        
        // تنسيق العنوان
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD3D3D3');

        // إضافة رؤوس الأعمدة في السطر الثاني
        $headers = ['م', 'اسم المدرسة', 'الرمز الوزاري', 'النوع', 'المراحل', 'نوع التعليم', 'نوع المبنى', 'الحالة', 'القطاع التعليمي'];
        $columnIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($columnIndex . '2', $header);
            $sheet->getStyle($columnIndex . '2')->getFont()->setBold(true);
            $sheet->getStyle($columnIndex . '2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columnIndex . '2')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE8E8E8');
            $columnIndex++;
        }

        // إضافة البيانات
        $rowIndex = 3;
        foreach ($data as $index => $school) {
            $genderLabel = config('schools.genders')[$school->gender] ?? $school->gender;
            $statusLabel = config('schools.statuses')[$school->status] ?? $school->status;
            $sheet->setCellValue('A' . $rowIndex, $index + 1);
            $sheet->setCellValue('B' . $rowIndex, $school->name);
            $sheet->setCellValue('C' . $rowIndex, $school->ministry_code);
            $sheet->setCellValue('D' . $rowIndex, $genderLabel);
            $sheet->setCellValue('E' . $rowIndex, $school->stage);
            $sheet->setCellValue('F' . $rowIndex, $school->school_type);
            $sheet->setCellValue('G' . $rowIndex, $school->building_type);
            $sheet->setCellValue('H' . $rowIndex, $statusLabel);
            $sheet->setCellValue('I' . $rowIndex, $school->educational_sector);
            
            // توسيط المحتوى
            $sheet->getStyle('A' . $rowIndex . ':I' . $rowIndex)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $rowIndex++;
        }

        // ضبط عرض الأعمدة تلقائيًا
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // حفظ الملف
        $fileName = 'schools_' . now()->format('Ymd_His') . '.xlsx';
        $filePath = public_path($fileName);
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $fileName;
    }
}
