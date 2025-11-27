<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;

use App\Models\HRM\WorkHourSetup;
use App\Http\Requests\WorkSetup\StoreWorkHourSetupRequest;
use App\Http\Requests\WorkSetup\UpdateWorkHourSetupRequest;
use App\Models\HRM\Branch;
use App\Services\WorkHours;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkHourSetupController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, WorkHours $work_hours)
    {
        try {
            $this->authorize('worksetup.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/hrm/workhour', [

            'filters'   => $work_hours->get($request->query()),
            'workhour'   => $work_hours->get($request->query()),
            'branch' => Branch::where('active', 1)->get(),
            'year' => $this->createYear(),
            'month' => $this->createMonth(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkHourSetupRequest $request)
    {
        try {
            $this->authorize('worksetup.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['active'] = 0;
        $data['user_id'] = Auth::id();
        $store = WorkHourSetup::create($data);
        if ($store) {
            return back()->with([
                'success' => true,
                'message' => 'Working hour setup created successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Working hour setup not created'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkHourSetup $workHourSetup)
    {
        try {
            $this->authorize('worksetup.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $workHourSetup->load('branch');
        if (!$workHourSetup) {
            return response()->json(['message' => 'Work houre setup not found'], 404);
        }
        return response()->json($workHourSetup);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkHourSetup $workHourSetup)
    {
        try {
            $this->authorize('worksetup.edit');
            
            return response()->json([
                'success' => true,
                'data' => $workHourSetup,
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
    public function update(UpdateWorkHourSetupRequest $request, WorkHourSetup $workHourSetup)
    {
        try {
            $this->authorize('worksetup.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $workHourSetup->update($request->validated());

        return back()->with([
            'message' => 'Working hour setup update successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkHourSetup $workHourSetup)
    {
        try {
            $this->authorize('worksetup.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $workHourSetup->delete();
            return back()->with([
                'message' => 'work hour setup delete successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete work hour setup setting.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $workHourSetup)
    {
        try {
            $this->authorize('worksetup.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $workHourSetup = WorkHourSetup::findOrFail($workHourSetup);
        $updated = $workHourSetup->update(['active' =>  $validated['active']]);
        if ($updated) {
            return back()->with([
                'message' => 'Working hour status updated successfully.'
            ]);
        }
        return back()->with([
            'message' => 'Failed to update status'
        ]);
    }

    public function createYear()
    {
        $a = array();
        for ($i = date('Y'); $i >= date('Y') - 5; $i--) {
            $a[$i] = $i;
        }
        return $a;
    }

    public function createMonth()
    {
        $a = array();
        for ($i = 1; $i <= 12; $i++) {
            $a[$i] = date("F", mktime(0, 0, 0, $i, $i));
        }
        return $a;
    }
}
