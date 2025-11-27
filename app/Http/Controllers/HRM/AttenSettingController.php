<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\AttenSetting;
use App\Http\Requests\AttenSetting\StoreAttenSettingRequest;
use App\Http\Requests\AttenSetting\UpdateAttenSettingRequest;
use Illuminate\Auth\Access\AuthorizationException;
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

        try {
            $this->authorize('attendanmst.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/hrm/attensetting', [
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
        try {
            $this->authorize('attendanmst.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        AttenSetting::create($request->validated());
        return redirect()->route('attensetting.index')->with('success', 'Attendance Setting Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AttenSetting $attensetting)
    {
        try {
            $this->authorize('attendanmst.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $attensetting->load('branch');
        return response()->json($attensetting);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttenSetting $attensetting): JsonResponse
    {
        try {
            $this->authorize('attendanmst.edit');

            return response()->json([
                'success' => true,
                'data' => $attensetting,
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
    public function update(UpdateAttenSettingRequest $request, AttenSetting $attensetting)
    {
        try {
            $this->authorize('attendanmst.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $attensetting->update($request->validated());
        return redirect()->route('attensetting.index')->with('success', 'Attendance Setting Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

    
    public function destroy(AttenSetting $attensetting)
    {
        try {
            $this->authorize('attendanmst.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

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
