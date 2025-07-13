<?php

namespace App\Http\Controllers;

use App\Models\AttenSetting;
use App\Http\Requests\AttenSetting\StoreAttenSettingRequest;
use App\Http\Requests\AttenSetting\UpdateAttenSettingRequest;
use App\Models\Branch;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;

class AttenSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('allpages/attensetting',[
            'attensetting' => AttenSetting::with('branch')->orderBy('id', 'desc')->get(),
            'branch' => Branch::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttenSettingRequest $request)
    {
        AttenSetting::create($request->validated());
        return redirect()->route('attensetting.index')->with('success', 'Attendance Setting Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AttenSetting $attensetting)
    {
         $attensetting->load('branch');
         return response()->json($attensetting);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttenSetting $attensetting): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $attensetting,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttenSettingRequest $request, AttenSetting $attensetting)
    {
        $attensetting->update($request->validated());
        return redirect()->route('attensetting.index')->with('success', 'Attendance Setting Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttenSetting $attensetting)
    {
        
        try {
            $attensetting->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete attendance setting.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
