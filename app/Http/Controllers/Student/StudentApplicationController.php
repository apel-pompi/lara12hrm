<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Student\StudentApplication;
use App\Http\Requests\StudentApplication\StoreStudentApplicationRequest;
use App\Http\Requests\StudentApplication\UpdateStudentApplicationRequest;
use App\Models\AgencySetting\WDocumentCheck;
use App\Models\AgencySetting\WDocumentType;
use Illuminate\Support\Facades\Response;
use App\Models\AgencySetting\Workflow;
use App\Models\AgencySetting\WorkflowStage;
use App\Models\Partner\Partner;
use App\Models\Product\Product;
use App\Models\Product\ProductFeesHd;
use App\Models\Student\ApplicationDocument;
use Illuminate\Support\Facades\Auth;
use App\Models\Student\Student;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentInService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentApplicationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Student $student)
    {
        $this->authorize('Application.index');

        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/application', [
            'student' => $student,
            'workflow' => Workflow::where('active', 1)->get(),
            'studentApplication' => StudentApplication::with(['student', 'workflow', 'partnerBranch.partner', 'product', 'stage', 'user'])->where('student_id', $student->id)->get(),
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentApplicationRequest $request)
    {
        $this->authorize('Application.store');

        $validated = $request->validated();
        $exists = StudentApplication::where('student_id', $validated['student_id'])
            ->where('workflow_id', $validated['workflow_id'])
            ->where('partner_branch_id', $validated['partner_branch_id'])
            ->where('product_id', $validated['product_id'])
            ->exists();

        if (! $exists) {
            StudentApplication::create([
                'student_id'              => $validated['student_id'],
                'workflow_id'              => $validated['workflow_id'],
                'partner_branch_id'        => $validated['partner_branch_id'],
                'product_id'               => $validated['product_id'],
                'stage'                    => $validated['stage'],
                'status'                   => $validated['status'],
                'saleprice'                => $validated['saleprice'],
                'user_id'                  => Auth::id()
            ]);
        }
        return redirect()
            ->route('studentApplication.index', $validated['student_id'])
            ->with('success', 'Student application created successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student, StudentApplication $studentApplication): JsonResponse
    {
        $this->authorize('Application.edit');

        return response()->json([
            'success' => true,
            'data' => $studentApplication,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentApplicationRequest $request, Student $student, StudentApplication $studentApplication)
    {
        $this->authorize('Application.update');

        // Ensure that this application belongs to the given student
        if ($studentApplication->student_id !== $student->id) {
            return response()->json([
                'success' => false,
                'message' => 'This application does not belong to the selected student.',
            ], 403);
        }

        // Get validated data
        $validated = $request->validated();

        // Update application
        $studentApplication->update($validated);
        StudentActivities::create([
            'student_id'    => $student->id,
            'title'         => 'Application updated',
            'fristactivity' => null,
            'lastactivity'  => null,
            'user_id'       => Auth::id(),
        ]);
        return redirect()
            ->route('studentApplication.index', $student->id)
            ->with('success', 'Student application updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student, StudentApplication $studentApplication)
    {
        $this->authorize('Application.destroy');

        try {
            $studentApplication->delete();
            StudentActivities::create([
                'student_id'    => $student->id,
                'title'         => 'Application deleted',
                'fristactivity' => null,
                'lastactivity'  => null,
                'user_id'       => Auth::id(),
            ]);
            return redirect()
                ->route('studentApplication.index', $student->id)
                ->with('success', 'Student Application deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('studentApplication.index', $student->id)
                ->with('error', 'Failed to delete Student Application.');
        }
    }

    public function partner($student, $partner)
    {

        $partners = Partner::whereRaw("FIND_IN_SET(?, workflow_id)", [$partner])
            ->leftJoin('partner_branches', 'partners.id', '=', 'partner_branches.partner_id')
            ->where('partners.active', 1)
            ->where('partner_branches.active', 1)
            ->get(['partners.id as partnerid', 'partners.name as partnername', 'partner_branches.id as partnerbranchid', 'partner_branches.branch_name as partnerbranch']);

        return response()->json($partners);
    }

    public function product($student, $product, $partner)
    {
        $product = Product::where('partner_id', $partner)
            ->where('active', 1)
            ->get();
        return response()->json($product);
    }


    public function appActivities(Student $student, StudentApplication $studentApplication)
    {
        $student->load('assainuser');

        $appdoclist = ApplicationDocument::with(['stage', 'documentid', 'user'])->where('student_id', $student->student_id)->get();

        $application = StudentApplication::with(['workflow', 'product', 'partnerBranch.partner', 'stage'])->where('student_id', $student->id)
            ->where('id', $studentApplication->id)
            ->firstOrFail();
        $productFeesHd = ProductFeesHd::with(['details', 'product', 'installment', 'user'])
            ->where('product_id', $application->product->id)
            ->get();


        // Payable sum
        $totalPayable = $productFeesHd->flatMap(fn($hd) => $hd->details)
            ->where('pay_type', 'Payable')
            ->sum('totalamount');

        // Income sum
        $totalIncome = $productFeesHd->flatMap(fn($hd) => $hd->details)
            ->where('pay_type', 'Income')
            ->sum('totalamount');

        return Inertia::render('allpages/Agency/Student/applicationActivities', [
            'student' => $student,
            'application' => $application,
            'appdoclist' => $appdoclist,
            'productFeesHd' => $productFeesHd,
            'totalNetAmount' => $productFeesHd->sum('netamount'),
            'total_payable' => $totalPayable,
            'total_income' => $totalIncome,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get()
        ]);
    }

    public function documentApplication(Student $student, StudentApplication $studentApplication)
    {
        $student->load('assainuser');
        $application = StudentApplication::with(['student', 'workflow.stages.documentChecks.documenttype', 'partnerBranch.partner', 'product', 'stage'])->where('student_id', $student->id)
            ->where('id', $studentApplication->id)
            ->firstOrFail();

        $studentFolder = public_path('storage/FileFolder/' . $student->student_id);

        if (!File::exists($studentFolder)) {
            return response()->json(['folders' => []]);
        }
        $foldersTree = $this->getFoldersTree($studentFolder);
        $folders = [
            'name' => basename($studentFolder), // SIDHA2510000001
            'children' => $foldersTree
        ];

        $appDoc = ApplicationDocument::with(['application', 'workflow', 'partner', 'product', 'stage', 'documentid', 'user'])->where('applcation_id', $studentApplication->id)->get();
        $documenttype = WDocumentType::where('active', 1)->get();

        $productFeesHd = ProductFeesHd::with(['details', 'product', 'installment', 'user'])
            ->where('product_id', $application->product->id)
            ->get();


        // Payable sum
        $totalPayable = $productFeesHd->flatMap(fn($hd) => $hd->details)
            ->where('pay_type', 'Payable')
            ->sum('totalamount');

        // Income sum
        $totalIncome = $productFeesHd->flatMap(fn($hd) => $hd->details)
            ->where('pay_type', 'Income')
            ->sum('totalamount');

        return Inertia::render('allpages/Agency/Student/applicationdocument', [
            'student' => $student,
            'application' => $application,
            'folderNames' => $folders,
            'appDoc' => $appDoc,
            'documenttype' => $documenttype,
            'productFeesHd' => $productFeesHd,
            'totalNetAmount' => $productFeesHd->sum('netamount'),
            'total_payable' => $totalPayable,
            'total_income' => $totalIncome,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get()
        ]);
    }

    public function documentNextStep(Student $student, StudentApplication $studentApplication)
    {
        $step = StudentApplication::with('stage')
            ->where('id', $studentApplication->id)
            ->where('student_id', $student->id)
            ->firstOrFail();
        $currentStageNumber = $step->stage->stage;
        $nextStage = WorkflowStage::where('workflow_id', $step->workflow_id)
            ->where('stage', $currentStageNumber + 1)
            ->first();
        if ($nextStage) {
            $step->update([
                'stage_id' => $nextStage->id,
            ]);
            return back()->with([
                'success' => true,
                'message' => 'Stage updated successfully. Proceeded to next step.'
            ]);
        }
        return back()->with([
            'success' => false,
            'message' => 'No next stage found.'
        ], 422);
    }

    public function documentBackStep(Student $student, StudentApplication $studentApplication)
    {
        $step = StudentApplication::with('stage')
            ->where('id', $studentApplication->id)
            ->where('student_id', $student->id)
            ->firstOrFail();
        $currentStageNumber = $step->stage->stage;
        $nextStage = WorkflowStage::where('workflow_id', $step->workflow_id)
            ->where('stage', $currentStageNumber - 1)
            ->first();
        if ($nextStage) {
            $step->update([
                'stage_id' => $nextStage->id,
            ]);
            return back()->with([
                'success' => true,
                'message' => 'Stage updated successfully. Proceeded to next step.'
            ]);
        }
        return back()->with([
            'success' => false,
            'message' => 'No back stage found.'
        ], 422);
    }
    // for nested array
    private function getFoldersTree($path)
    {
        $tree = [];
        $subFolders = File::directories($path);

        foreach ($subFolders as $folder) {
            $tree[] = [
                'name' => basename($folder),
                'children' => $this->getFoldersTree($folder) // recursive call
            ];
        }

        return $tree;
    }

    public function docAppStore(Student $student, StudentApplication $studentApplication, Request $request)
    {
        $application = StudentApplication::with(['student', 'workflow.stages.documentChecks.documenttype', 'partnerBranch.partner', 'product', 'user'])->where('student_id', $student->id)
            ->where('id', $studentApplication->id)
            ->firstOrFail();
        $studentID = $application->student->student_id;
        $workflow = $application->workflow->name;
        $partner = $application->partnerBranch->partner->name;
        $product = $application->product->name;
        $request->validate([
            'folder' => 'required|string',
            'file'     => 'required|file|max:300|mimes:jpg,jpeg,png', // max 300 KB, only jpg,jpeg,png
            'stage_id' => 'required|exists:workflow_stages,id',
            'doc_id' => 'required|exists:w_document_types,id',
        ]);

        if ($request->folder == $studentID) {
            $basePath = "FileFolder/{$studentID}";
        } elseif ($request->folder == $workflow) {
            $basePath = "FileFolder/{$studentID}/{$workflow}";
        } elseif ($request->folder == $partner) {
            $basePath = "FileFolder/{$studentID}/{$workflow}/{$partner}";
        } elseif ($request->folder == $product) {
            $basePath = "FileFolder/{$studentID}/{$workflow}/{$partner}/{$product}";
        } else {
            return back()->with(['message' => 'Please select a valid folder'], 422);
        }


        $exists = ApplicationDocument::where('stage_id', $request->stage_id)->where('doc_id', $request->doc_id)->where('student_id', $studentID)->where('applcation_id', $studentApplication->id)->exists();

        if (!$exists) {
            $uploadedFile = $request->file('file');
            $file_name = time() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->storeAs($basePath, $file_name, 'public');

            ApplicationDocument::create([
                'student_id' => $studentID,
                'applcation_id' => $studentApplication->id,
                'workflow_id' => $application->workflow->id,
                'partner_id' => $application->partnerBranch->partner->id,
                'product_id' => $application->product->id,
                'stage_id' => $request->stage_id,
                'doc_id' => $request->doc_id,
                'docname' => $file_name,
                'user_id' => Auth::id(),
            ]);
        } else {
            return back()->with(['message' => 'Document already exists! Please upload another one.',]);
        }
        return redirect()
            ->route('studentApplication.documentApplication', [
                $student->id,
                $studentApplication->id
            ])
            ->with('success', 'Document uploaded successfully!');
    }

    public function deleteAppDocument(Student $student, StudentApplication $studentApplication, ApplicationDocument $document)
    {
        $filePath = "FileFolder/{$document->student_id}/{$document->workflow->name}/{$document->partner->name}/{$document->product->name}/{$document->docname}";

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $document->delete();

        return back()->with('message', 'Document deleted successfully!');
    }

    public function downloadAppDocument(Student $student, StudentApplication $studentApplication, ApplicationDocument $document)
    {
        $filePath = "FileFolder/{$document->student_id}/{$document->workflow->name}/{$document->partner->name}/{$document->product->name}/{$document->docname}";

        if (!Storage::disk('public')->exists($filePath)) {
            return back()->with('message', 'File not found!');
        }

        $file = Storage::disk('public')->path($filePath);
        $filename = basename($filePath);

        return Response::download($file, $filename, [
            'Content-Type' => Storage::disk('public')->mimeType($filePath),
        ]);
    }


    public function updateCheckList(Student $student, StudentApplication $studentApplication, Request $request)
    {

        $this->authorize('workflowDocumentCheck.store');

        $validated = $request->validate([
            'workflow_id' => 'required|integer',
            'doctype_id' => 'required|integer',
            'workstage_id' => 'required|integer',
        ]);
        $documentcheck = WDocumentCheck::create([
            'workflow_id' => $validated['workflow_id'],
            'doctype_id' => $validated['doctype_id'],
            'workstage_id' => $validated['workstage_id'],
            'user_id' => Auth::id(),
            'active' => '1',
        ]);
        if ($documentcheck) {
            return redirect()
                ->route('studentApplication.documentApplication', [
                    $student->id,
                    $studentApplication->id
                ])
                ->with('success', 'Document uploaded successfully!');
        } else {
            return back()->with('message', 'Document check not updated');
        }
    }
    public function notesApplication(Student $student, StudentApplication $studentApplication)
    {
        $student->load('assainuser');
        $application = StudentApplication::with(['workflow', 'product', 'partnerBranch.partner', 'stage'])->where('student_id', $student->id)
            ->where('id', $studentApplication->id)
            ->firstOrFail();

        $productFeesHd = ProductFeesHd::with(['details', 'product', 'installment', 'user'])
            ->where('product_id', $application->product->id)
            ->get();


        // Payable sum
        $totalPayable = $productFeesHd->flatMap(fn($hd) => $hd->details)
            ->where('pay_type', 'Payable')
            ->sum('totalamount');

        // Income sum
        $totalIncome = $productFeesHd->flatMap(fn($hd) => $hd->details)
            ->where('pay_type', 'Income')
            ->sum('totalamount');
        return Inertia::render('allpages/Agency/Student/applicationnotes', [
            'student' => $student,
            'application' => $application,
            'productFeesHd' => $productFeesHd,
            'totalNetAmount' => $productFeesHd->sum('netamount'),
            'total_payable' => $totalPayable,
            'total_income' => $totalIncome,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get()
        ]);
    }

    public function tasksApplication(Student $student, StudentApplication $studentApplication)
    {
        $student->load('assainuser');
        $application = StudentApplication::with(['workflow', 'product', 'partnerBranch.partner', 'stage'])->where('student_id', $student->id)
            ->where('id', $studentApplication->id)
            ->firstOrFail();

        $productFeesHd = ProductFeesHd::with(['details', 'product', 'installment', 'user'])
            ->where('product_id', $application->product->id)
            ->get();


        // Payable sum
        $totalPayable = $productFeesHd->flatMap(fn($hd) => $hd->details)
            ->where('pay_type', 'Payable')
            ->sum('totalamount');

        // Income sum
        $totalIncome = $productFeesHd->flatMap(fn($hd) => $hd->details)
            ->where('pay_type', 'Income')
            ->sum('totalamount');
        return Inertia::render('allpages/Agency/Student/applicationtasks', [
            'student' => $student,
            'application' => $application,
            'productFeesHd' => $productFeesHd,
            'totalNetAmount' => $productFeesHd->sum('netamount'),
            'total_payable' => $totalPayable,
            'total_income' => $totalIncome,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get()
        ]);
    }

    public function paymentApplication(Student $student, StudentApplication $studentApplication)
    {
        $student->load('assainuser');
        $application = StudentApplication::with(['workflow', 'product', 'partnerBranch.partner', 'stage'])->where('student_id', $student->id)
            ->where('id', $studentApplication->id)
            ->firstOrFail();

        $productFeesHd = ProductFeesHd::with(['details', 'product', 'installment', 'user'])
            ->where('product_id', $application->product->id)
            ->get();


        // Payable sum
        $totalPayable = $productFeesHd->flatMap(fn($hd) => $hd->details)
            ->where('pay_type', 'Payable')
            ->sum('totalamount');

        // Income sum
        $totalIncome = $productFeesHd->flatMap(fn($hd) => $hd->details)
            ->where('pay_type', 'Income')
            ->sum('totalamount');
        return Inertia::render('allpages/Agency/Student/applicationpayment', [
            'student' => $student,
            'application' => $application,
            'productFeesHd' => $productFeesHd,
            'totalNetAmount' => $productFeesHd->sum('netamount'),
            'total_payable' => $totalPayable,
            'total_income' => $totalIncome,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get()
        ]);
    }
}
