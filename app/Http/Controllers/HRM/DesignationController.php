<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Designation;
use App\Http\Requests\Designation\StoreDesignationRequest;
use App\Http\Requests\Designation\UpdateDesignationRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class DesignationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('designation.index');

        return Inertia::render('allpages/designation',[
            'designation' => Designation::orderBy('id', 'desc')->get()
        ]);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDesignationRequest $request)
    {
        $this->authorize('designation.store');

        Designation::create($request->validated());
        return redirect()->route('designation.index')->with('success', 'Designation Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        $this->authorize('designation.show');

        if (!$designation) {
            return response()->json(['message' => 'Designation not found'], 404);
        }
        return response()->json($designation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        $this->authorize('designation.edit');

        return response()->json([
            'success' => true,
            'data' => $designation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDesignationRequest $request, Designation $designation)
    {
        $this->authorize('designation.update');

        $designation->update($request->validated());
        return redirect()->route('designation.index')->with('success', 'Designation Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $this->authorize('designation.destroy');

        try {
            $designation->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete designation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
