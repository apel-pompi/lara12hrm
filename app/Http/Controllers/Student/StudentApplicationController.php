<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Student\StudentApplication;
use App\Http\Requests\StudentApplication\StoreStudentApplicationRequest;
use App\Http\Requests\StudentApplication\UpdateStudentApplicationRequest;
use App\Models\Partner\Partner;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Student\Student;
use App\Models\Student\StudentActivities;
use App\Models\Workflow;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;

class StudentApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Student $student)
    {
        //dd($student->id);
        return Inertia::render('allpages/Agency/Student/application', [
            'student' => $student,
            'workflow' => Workflow::where('active', 1)->get(),
            'studentApplication' => StudentApplication::with(['student', 'workflow', 'partnerBranch.partner', 'product', 'user'])->where('student_id', $student->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentApplicationRequest $request)
    {
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

            StudentActivities::create([
                'student_id'    => $validated['student_id'],
                'title'         => 'Application added',
                'fristactivity' => null,
                'lastactivity'  => null,
                'user_id'       => Auth::id(),
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
            ->whereRaw("FIND_IN_SET(?, partner_branch_id)", [$product])
            ->where('active', 1)
            ->get();
        return response()->json($product);
    }


    public function editApplication(Student $student, StudentApplication $studentApplication)
    {
        return Inertia::render('allpages/Agency/Student/applicationedit',[
            'student' => $student,
        ]);
    }
}
