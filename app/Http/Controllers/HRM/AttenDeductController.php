<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttenDeduct\StoreAttenDeductRequest;
use App\Http\Requests\AttenDeduct\UpdateAttenDeductRequest;
use App\Models\HRM\AttenDeduct;
use App\Models\HRM\Branch;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;

class AttenDeductController extends Controller
{
    use AuthorizesRequests;
    
    public function index(){

        try {
            $this->authorize('deduct.index');

        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/hrm/attendeduct', [
            'attendeduct' => AttenDeduct::with(['branch' => function ($query) {
                $query->where('active', 1);
            }])->get(),
            'branch' => Branch::where('active', 1)->get(),
        ]);
    }

    public function store(StoreAttenDeductRequest $request)
    {
        try {
            $this->authorize('deduct.store');

        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $store = AttenDeduct::create($data);
        if ($store) {
            return back()->with([
                'success' => true,
                'message' => 'Attendance Deduct created successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Attendance Deduct not created'
            ]);
        }
    }

     /**
     * Display the specified resource.
     */
    public function show(AttenDeduct $attendeduct)
    {
        try {
            $this->authorize('deduct.show');

        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $attendeduct->load('branch');
        return response()->json($attendeduct);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttenDeduct $attendeduct): JsonResponse
    {
        try {
            $this->authorize('deduct.edit');

            return response()->json([
            'success' => true,
            'data' => $attendeduct,
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
    public function update(UpdateAttenDeductRequest $request, AttenDeduct $attendeduct)
    {
        try {
            $this->authorize('deduct.update');

        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $attendeduct->update($request->validated());
        return redirect()->route('attendeduct.index')->with('success', 'Attendance Deduct Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttenDeduct $attendeduct)
    {
        try {
            $this->authorize('deduct.destroy');

        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $attendeduct->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete attendance deduct.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
