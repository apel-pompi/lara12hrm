<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;

use App\Imports\StudentImport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;

class ExcelImportController extends Controller
{
    use AuthorizesRequests;

    public function showImportForm()
    {
        //$this->authorize('StudentLead.index');
        return Inertia::render('allpages/Excel/Import');
    }

    public function import(Request $request)
    {
        //$this->authorize('StudentLead.import');
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $import = new StudentImport();
            Excel::import($import, $request->file('excel_file'));
            $processed = $import->getProcessedCount();
            $success = $import->getSuccessCount();

            return redirect()->back()->with(
                'success',
                "Import completed! Processed: {$processed}, Success: {$success}, Failed: " . ($processed - $success)
            );

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()->back()
                ->with('error', 'Validation errors occurred:')
                ->with('error', $errorMessages);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        //$this->authorize('StudentLead.download');
        $filePath = public_path('storage/templates/student_import_template.xlsx');

        if (!file_exists($filePath)) {
            $created = $this->createStudentTemplate();
            if (!$created) {
                return back()->with('error', 'Template creation failed. Please contact administrator.');
            }
        }

        if (!file_exists($filePath)) {
            return back()->with('error', 'Template file not found.');
        }

        return response()->download($filePath, 'student_import_template_' . date('Y-m-d') . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function createStudentTemplate()
    {
        try {
            $templatePath = public_path('storage/templates');
            if (!file_exists($templatePath)) {
                mkdir($templatePath, 0755, true);
            }

            $filePath = $templatePath . '/student_import_template.xlsx';

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = [
                'first_name',
                'last_name',
                'gender',
                'phone',
                'destination_country',
                'source',
                'counsilor_name',
            ];

            $sheet->fromArray($headers, null, 'A1');

            // Header styling
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2E86AB']
                ],
                'alignment' => ['horizontal' => 'center']
            ];
            $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

            // Force Phone column as TEXT
            $sheet->getStyle('D2:D1000')->getNumberFormat()->setFormatCode('@');

            // Gender validation
            $genderValidation = $sheet->getCell('C2')->getDataValidation();
            $genderValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $genderValidation->setFormula1('"Male,Female,Other"');
            $genderValidation->setAllowBlank(false);
            $genderValidation->setShowDropDown(true);

            for ($row = 2; $row <= 1000; $row++) {
                $sheet->getCell("C{$row}")->setDataValidation(clone $genderValidation);
            }

            // Auto-size columns
            foreach (range('A', 'G') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Clear instructions as comment instead of extra columns
            $sheet->getComment('A1')
                ->getText()->createTextRun("Instructions:\n- All fields required\n- Phone: start with '0\n- Gender: use dropdown");

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);

            return true;
        } catch (\Exception $e) {
            $e->getMessage();
            return false;
        }
    }

    
}
