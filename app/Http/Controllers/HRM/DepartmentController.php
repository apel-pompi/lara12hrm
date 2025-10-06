<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Department;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('department.index');

        return Inertia::render('allpages/hrm/department',[
            'department' => Department::orderBy('id', 'desc')->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $this->authorize('department.store');

        Department::create($request->validated());
        return redirect()->route('department.index')->with('success', 'Department Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $this->authorize('department.show');

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }
        return response()->json($department);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $this->authorize('department.edit');

        return response()->json([
            'success' => true,
            'data' => $department,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $this->authorize('department.update');

        $department->update($request->validated());
        return redirect()->route('department.index')->with('success', 'Department Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $this->authorize('department.destroy');

        try {
            $department->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete department.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
