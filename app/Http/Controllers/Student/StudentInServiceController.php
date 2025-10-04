<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentInService;
use App\Http\Requests\StudentInService\StoreStudentInServiceRequest;
use App\Http\Requests\StudentInService\UpdateStudentInServiceRequest;
use App\Models\AgencySetting\Workflow;
use App\Models\Student\Student;
use App\Models\Student\StudentApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentInServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Student $student)
    {
        return Inertia::render('allpages/Agency/Student/interestedservice', [
            'student' => $student,
            'workflow' => Workflow::where('active', 1)->get(),
            'studentService' => StudentInService::with(['student', 'workflow', 'partnerBranch.partner', 'product', 'productfees', 'user'])->where('student_id',$student->id)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Student $student)
    {
        $data = StudentInService::findOrFail($request->studentInService);
        if ($data) {
            StudentApplication::create([
                'student_id'              => $data['student_id'],
                'workflow_id'              => $data['workflow_id'],
                'partner_branch_id'        => $data['partner_branch_id'],
                'product_id'               => $data['product_id'],
                'stage'                    => null,
                'status'                   => 'In Progress',
                'saleprice'                => null,
                'user_id'                  => Auth::id()
            ]);

            $data->update([
                'status' => 'converted',
            ]);
        }
        return redirect()
            ->route('studentApplication.index', $student->id)
            ->with('success', 'Student application created.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentInServiceRequest $request)
    {
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

        return redirect()
            ->route('studentInService.index', $validated['student_id'])
            ->with('success', 'Student Interested Service created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(StudentInService $studentInService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentInService $studentInService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentInServiceRequest $request, StudentInService $studentInService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student, StudentInService $studentInService)
    {

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
