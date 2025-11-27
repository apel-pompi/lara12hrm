<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentSource;
use App\Http\Requests\StudentSource\StoreStudentSourceRequest;
use App\Http\Requests\StudentSource\UpdateStudentSourceRequest;
use App\Services\Agency\Setting\StudentSourceService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentSourceController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, StudentSourceService $studentSourceService)
    {
        try {
            $this->authorize('StudentSource.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return Inertia::render('allpages/Agency/Setting/studentsource', [
            'searchName' => StudentSource::select('name')->get(),
            'studentSource' => $studentSourceService->get($request->query()),
            'filters'   => $studentSourceService->get($request->query()),
        ]);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentSourceRequest $request)
    {
        try {
            $this->authorize('StudentSource.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $validated = $request->validated();
        StudentSource::create([
            'name'              => $validated['name'],
            'adddate'           => date('y-m-d'),
            'user_id'           => Auth::id(), // logged-in user
            'active'            => $validated['active'] ?? 0,
        ]);

        return redirect()
            ->route('studentSource.index')
            ->with('success', 'Student source created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentSource $studentSource)
    {
        try {
            $this->authorize('StudentSource.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        if (!$studentSource) {
            return response()->json(['message' => 'Student source not found'], 404);
        }
        return response()->json($studentSource);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentSource $studentSource)
    {
        try {
            $this->authorize('StudentSource.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return response()->json([
            'success' => true,
            'data' => $studentSource,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentSourceRequest $request, StudentSource $studentSource)
    {
        try {
            $this->authorize('StudentSource.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $studentSource->update($request->validated());
        return redirect()->route('studentSource.index')->with('success', 'Student stage update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentSource $studentSource)
    {
        try {
            $this->authorize('StudentSource.destory');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        try {
            $studentSource->delete();

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
            $this->authorize('StudentSource.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $student = StudentSource::findOrFail($student);
        $updated = $student->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('studentSource.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }
}
