<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Http\Requests\Designation\StoreDesignationRequest;
use App\Http\Requests\Designation\UpdateDesignationRequest;
use Inertia\Inertia;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('allpages/designation',[
            'designation' => Designation::orderBy('id', 'desc')->get()
        ]);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDesignationRequest $request)
    {
        Designation::create($request->validated());
        return redirect()->route('designation.index')->with('success', 'Designation Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
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
        $designation->update($request->validated());
        return redirect()->route('designation.index')->with('success', 'Designation Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
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
