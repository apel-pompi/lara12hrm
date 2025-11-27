<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Branch;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use Illuminate\Auth\Access\AuthorizationException;
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
        try {
            $this->authorize('branch.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/hrm/branch', [
            'branch' => Branch::orderBy('id', 'desc')->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request)
    {
        try {
            $this->authorize('branch.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        Branch::create($request->validated());
        return redirect()->route('branch.index')->with('success', 'Branch Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        try {
            $this->authorize('branch.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        try {
            $this->authorize('branch.edit');
            return response()->json([
                'success' => true,
                'data' => $branch,
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        try {
            $this->authorize('branch.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $branch->update($request->validated());
        return redirect()->route('branch.index')->with('success', 'Branch Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        try {
            $this->authorize('branch.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
