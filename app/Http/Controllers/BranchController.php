<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('branch/index',[
            'branch' => Branch::orderBy('id', 'desc')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request)
    {
        Branch::create($request->validated());
        return redirect()->route('branch.index')->with('success', 'Branch Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
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
        $branch->update($request->validated());
        return redirect()->route('branch.index')->with('success', 'Branch Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
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
