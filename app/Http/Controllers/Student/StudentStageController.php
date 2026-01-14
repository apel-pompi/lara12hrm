<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentStage;
use App\Http\Requests\StudentStage\StoreStudentStageRequest;
use App\Http\Requests\StudentStage\UpdateStudentStageRequest;
use App\Services\Agency\Setting\StudentStageService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentStageController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, StudentStageService $studentStageService)
    {
        try {
            $this->authorize('StudentStage.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $perPage = $request->query('per_page', 10);
        return Inertia::render('allpages/Agency/Setting/studentstage', [
            'searchName' => StudentStage::select('name')->get(),
            'studentStage' => $studentStageService->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters'   => $studentStageService->get($request->query()),
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentStageRequest $request)
    {
        try {
            $this->authorize('StudentStage.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validated();
        StudentStage::create([
            'name'              => $validated['name'],
            'adddate'           => date('y-m-d'),
            'user_id'           => Auth::id(), // logged-in user
            'active'            => $validated['active'] ?? 0,
        ]);

        return redirect()
            ->route('studentStage.index')
            ->with('success', 'Student stage created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentStage $studentStage)
    {
        try {
            $this->authorize('StudentStage.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        if (!$studentStage) {
            return response()->json(['message' => 'Student stage not found'], 404);
        }
        return response()->json($studentStage);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentStage $studentStage)
    {
        try {
            $this->authorize('StudentStage.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return response()->json([
            'success' => true,
            'data' => $studentStage,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentStageRequest $request, StudentStage $studentStage)
    {
        try {
            $this->authorize('StudentStage.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $studentStage->update($request->validated());
        return redirect()->route('studentStage.index')->with('success', 'Student stage update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentStage $studentStage)
    {
        try {
            $this->authorize('StudentStage.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        try {
            $studentStage->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete student stage.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateStatus(Request $request, $student)
    {
        try {
            $this->authorize('StudentStage.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $student = StudentStage::findOrFail($student);
        $updated = $student->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('studentStage.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }

}
