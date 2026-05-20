<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentInService;
use App\Http\Requests\StudentInService\StoreStudentInServiceRequest;
use App\Http\Requests\StudentInService\UpdateStudentInServiceRequest;
use App\Models\Accounts\ChartOfAccount;
use App\Models\Accounts\CodesParam;
use App\Models\AgencySetting\Workflow;
use App\Models\AgencySetting\WorkflowStage;
use App\Models\Partner\PartnerBranch;
use App\Models\Product\Product;
use App\Models\Student\Student;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentApplication;
use App\Models\Student\StudentQuotationHD;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class StudentInServiceController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Student $student)
    {
        try {
            $this->authorize('Service.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/interestedservice', [
            'student' => $student,
            'workflow' => Workflow::where('active', 1)->get(),
            'studentService' => StudentInService::with(['student', 'workflow', 'partnerBranch.partner', 'product', 'productfees', 'user'])->where('student_id', $student->id)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Student $student)
    {
        try {
            $this->authorize('Service.create');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $checkID = Student::find($student->id);
        if (!$checkID || !$checkID->student_id) {
            return back()->with([
                'success' => false,
                'message' => 'Student ID not created. Please create student ID first.'
            ]);
        }

        $data = StudentInService::findOrFail($request->studentInService);
        $stage = WorkflowStage::where('workflow_id', $data->workflow_id)
            ->where('stage', 1)
            ->first();

        if ($data && $stage) {
            $student = Student::where('id', $student->id)->first();
            if ($student && $student->student_id) {
                $basePath = public_path('storage/FileFolder');

                // Base folder
                if (!File::exists($basePath)) {
                    File::makeDirectory($basePath, 0755, true);
                }

                // Student folder
                $studentFolder = $basePath . '/' . $student->student_id;
                if (!File::exists($studentFolder)) {
                    File::makeDirectory($studentFolder, 0755, true);
                }

                // Workflow folder
                $workflow = Workflow::find($data->workflow_id, ['name']);
                if ($workflow) {
                    $workflowFolder = $studentFolder . '/' . $workflow->name;
                    if (!File::exists($workflowFolder)) {
                        File::makeDirectory($workflowFolder, 0755, true);
                    }

                    // Partner folder
                    $partnerBranch = PartnerBranch::join('partners', 'partners.id', '=', 'partner_branches.partner_id')
                        ->where('partner_branches.id', $data->partner_branch_id)
                        ->where('partner_branches.active', 1)
                        ->where('partners.active', 1)
                        ->select('partner_branches.id', 'partner_branches.branch_name', 'partners.name as partner_name')
                        ->first();

                    if ($partnerBranch) {
                        $partnerFolder = $workflowFolder . '/' . $partnerBranch->partner_name;
                        if (!File::exists($partnerFolder)) {
                            File::makeDirectory($partnerFolder, 0755, true);
                        }

                        // Product folder
                        $product = Product::find($data->product_id, ['name']);
                        if ($product) {
                            $productFolder = $partnerFolder . '/' . $product->name;
                            if (!File::exists($productFolder)) {
                                File::makeDirectory($productFolder, 0755, true);
                            }
                        }
                    }
                }
            }
            $exists = StudentApplication::where('student_id', $student->id)
                ->where('workflow_id', $data->workflow_id)
                ->where('partner_branch_id', $data->partner_branch_id)
                ->where('product_id', $data->product_id)
                ->exists();
            if (! $exists) {
                StudentApplication::create([
                    'student_id'        => $student->id,
                    'workflow_id'       => $data->workflow_id,
                    'partner_branch_id' => $data->partner_branch_id,
                    'product_id'        => $data->product_id,
                    'stage_id'          => $stage->id,
                    'status'            => 'In Progress',
                    'saleprice'         => null,
                    'user_id'           => Auth::id(),
                ]);

                $data->update(['status' => 'converted']);

                //chart of account table update 
                $codesParam = CodesParam::where('type', 'Student Advance')->first();
                if ($codesParam && $codesParam->cracc) {
                    $cracc = $codesParam->cracc;
                    $parts = explode('-', $cracc);
                    if (count($parts) >= 3) {
                        $prefix = implode('-', array_slice($parts, 0, 3));

                        // Find next suffix
                        $maxSuffix = ChartOfAccount::where('accountcode', 'like', "$prefix-%")
                            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(accountcode, '-', -1) AS UNSIGNED)) as max_val")
                            ->value('max_val');

                        $nextSuffixVal = $maxSuffix ? ((int)$maxSuffix + 1) : 1;
                        $nextSuffix = str_pad($nextSuffixVal, 3, '0', STR_PAD_LEFT);
                        $newAccountCode = $prefix . '-' . $nextSuffix;

                        // Check if account already exists for this student
                        $existingCOA = ChartOfAccount::where('description', $checkID->student_id)->first();
                        if (!$existingCOA) {
                            $originalCOA = ChartOfAccount::where('accountcode', $cracc)->first();
                            if ($originalCOA) {
                                ChartOfAccount::create([
                                    'groupone'       => $originalCOA->groupone,
                                    'grouptwo'       => $originalCOA->grouptwo,
                                    'groupthree'     => $originalCOA->groupthree,
                                    'groupfour'      => $originalCOA->groupfour,
                                    'accountcode'    => $newAccountCode,
                                    'description'    => $checkID->student_id,
                                    'accounttype'    => $originalCOA->accounttype,
                                    'accountusage'   => $originalCOA->accountusage,
                                    'analyticalcode' => $originalCOA->analyticalcode,
                                    'user_id'        => Auth::id(),
                                    'active'         => 1,
                                ]);
                            }
                        }
                    }
                }
            } else {
                return back()->with('message', 'Student application is allready created');
            }

            StudentActivities::create([
                'student_id' => $student->id,
                'title' => "has created student application's",
                'fristactivity' => null,
                'lastactivity' => null,
                'user_id' => Auth::id()
            ]);
        }
        return back()->with('message', 'Student application created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentInServiceRequest $request)
    {
        try {
            $this->authorize('Service.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $validated = $request->validated();

        $exists = StudentInService::where('student_id', $validated['student_id'])
            ->where('workflow_id', $validated['workflow_id'])
            ->where('partner_branch_id', $validated['partner_branch_id'])
            ->where('product_id', $validated['product_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'duplicate' => 'This Student Interested Service already exists for the selected workflow, partner branch and product.'
            ]);
        }

        StudentInService::create([
            'student_id'        => $validated['student_id'],
            'workflow_id'       => $validated['workflow_id'],
            'partner_branch_id' => $validated['partner_branch_id'],
            'product_id'        => $validated['product_id'],
            'startdate'         => $validated['startdate'],
            'enddate'           => $validated['enddate'],
            'status'            => 'Draft',
            'user_id'           => Auth::id()
        ]);

        StudentActivities::create([
            'student_id' => $validated['student_id'],
            'title' => "has created student service's",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        return redirect()
            ->route('studentInService.index', $validated['student_id'])
            ->with('success', 'Student Interested Service created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(StudentInService $studentInService)
    {
        try {
            $this->authorize('Service.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentInService $studentInService)
    {
        try {
            $this->authorize('Service.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentInServiceRequest $request, StudentInService $studentInService)
    {
        try {
            $this->authorize('Service.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student, StudentInService $studentInService)
    {
        try {
            $this->authorize('Service.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {

            $exists = StudentApplication::where([
                'student_id'       => $student->id,
                'workflow_id'      => $studentInService->workflow_id,
                'partner_branch_id' => $studentInService->partner_branch_id,
                'product_id'       => $studentInService->product_id,
            ])->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'Data already exists in student application. Deletion not allowed.',
                ], 422);
            }

            $exist = StudentQuotationHD::where([
                'service_id'       => $studentInService->id
            ])->exists();

            if ($exist) {
                return response()->json([
                    'message' => 'Student Quotation already exists. Deletion not allowed.',
                ], 422);
            }

            StudentActivities::create([
                'student_id' => $student->id,
                'title' => "has deleted student service's",
                'fristactivity' => null,
                'lastactivity' => null,
                'user_id' => Auth::id()
            ]);

            $studentInService->delete();

            return response()->json([
                'message' => 'Student Interested Service deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Student Interested Service.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
