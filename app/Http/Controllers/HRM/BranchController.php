<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Branch;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class BranchController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('branch.index');

        return Inertia::render('allpages/branch',[
            'branch' => Branch::orderBy('id', 'desc')->get()
        ]);
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request)
    {
        $this->authorize('branch.store');

        Branch::create($request->validated());
        return redirect()->route('branch.index')->with('success', 'Branch Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        $this->authorize('branch.show');

        if (!$branch) {
            return response()->json(['message' => 'Branch not found'], 404);
        }
        return response()->json($branch);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch): JsonResponse
    {
        $this->authorize('branch.edit');

        return response()->json([
            'success' => true,
            'data' => $branch,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $this->authorize('branch.update');

        $branch->update($request->validated());
        return redirect()->route('branch.index')->with('success', 'Branch Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $this->authorize('branch.destroy');

        try {
            $branch->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete branch.',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }
}
