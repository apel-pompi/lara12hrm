<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;

use App\Imports\StudentImport;
use App\Models\Default\Country;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;

class ExcelImportController extends Controller
{
    use AuthorizesRequests;

    public function showImportForm()
    {
        try {
            $this->authorize('StudentLead.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/Excel/Import');
    }

    public function import(Request $request)
    {
        try {
            $this->authorize('StudentLead.import');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

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
        try {
            $this->authorize('StudentLead.download');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

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

            /** -------------------------
             * MAIN SHEET
             * ------------------------*/
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('students');

            $headers = [
                'first_name',
                'last_name',
                'gender',
                'phone',
                'destination_country',
                'source',
                'counselor_name',
            ];

            $sheet->fromArray($headers, null, 'A1');

            /** Header Style */
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2E86AB']
                ],
                'alignment' => ['horizontal' => 'center']
            ];

            $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

            /** Phone column as TEXT */
            $sheet->getStyle('D2:D1000')
                ->getNumberFormat()
                ->setFormatCode('@');

            /** Gender Dropdown */
            $genderList = '"Male,Female,Other"';

            $genderValidation = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
            $genderValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $genderValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $genderValidation->setAllowBlank(false);
            $genderValidation->setShowDropDown(true);
            $genderValidation->setFormula1($genderList);

            for ($row = 2; $row <= 1000; $row++) {
                $sheet->getCell("C{$row}")->setDataValidation(clone $genderValidation);
            }

            /** Auto size columns */
            foreach (range('A', 'G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            /** Instruction comment */
            $sheet->getComment('A1')
                ->getText()->createTextRun(
                    "Instructions:\n" .
                        "- All fields are required\n" .
                        "- Phone must start with 0\n" .
                        "- Gender uses dropdown\n" .
                        "- Country must be selected from list"
                );

            /** -------------------------
             * COUNTRIES (Hidden Sheet)
             * ------------------------*/
            $countries = Country::where('status', 1)
                ->orderBy('name')
                ->pluck('name')
                ->toArray();

            $countrySheet = $spreadsheet->createSheet();
            $countrySheet->setTitle('countries');

            foreach ($countries as $index => $country) {
                $countrySheet->setCellValue("A" . ($index + 1), $country);
            }

            /** Hide country sheet */
            $countrySheet->setSheetState(
                \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN
            );

            /** Country Dropdown */
            $countryValidation = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
            $countryValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $countryValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $countryValidation->setAllowBlank(false);
            $countryValidation->setShowDropDown(true);
            $countryValidation->setFormula1('countries!$A$1:$A$' . count($countries));

            for ($row = 2; $row <= 1000; $row++) {
                $sheet->getCell("E{$row}")->setDataValidation(clone $countryValidation);
            }

            /** -------------------------
             * SAVE FILE
             * ------------------------*/
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);

            return true;
        } catch (\Exception $e) {
            logger()->error('Student Template Error: ' . $e->getMessage());
            return false;
        }
    }
}
