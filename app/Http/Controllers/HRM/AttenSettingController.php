<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\AttenSetting;
use App\Http\Requests\AttenSetting\StoreAttenSettingRequest;
use App\Http\Requests\AttenSetting\UpdateAttenSettingRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\HRM\Branch;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;

class AttenSettingController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $this->authorize('attendanmst.index');

        return Inertia::render('allpages/attensetting', [
            'attensetting' => AttenSetting::with(['branch' => function ($query) {
                $query->where('active', 1);
            }])->get(),
            'branch' => Branch::where('active', 1)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttenSettingRequest $request)
    {
        $this->authorize('attendanmst.store');

        AttenSetting::create($request->validated());
        return redirect()->route('attensetting.index')->with('success', 'Attendance Setting Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AttenSetting $attensetting)
    {
        $this->authorize('attendanmst.show');

        $attensetting->load('branch');
        return response()->json($attensetting);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttenSetting $attensetting): JsonResponse
    {
        $this->authorize('attendanmst.edit');

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
        $this->authorize('attendanmst.update');

        $attensetting->update($request->validated());
        return redirect()->route('attensetting.index')->with('success', 'Attendance Setting Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttenSetting $attensetting)
    {
        $this->authorize('attendanmst.destroy');
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
