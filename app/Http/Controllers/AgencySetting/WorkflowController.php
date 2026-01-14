<?php

namespace App\Http\Controllers\AgencySetting;

use App\Http\Controllers\Controller;
use App\Models\AgencySetting\Workflow;
use App\Models\AgencySetting\WorkflowStage;
use App\Services\Agency\Setting\WorkflowService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WorkflowController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, WorkflowService $workflow)
    {
        try {
            $this->authorize('workflow.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $perPage = $request->query('per_page', 10);
        return Inertia::render('allpages/Agency/Setting/workflow', [
            'filters'   => $workflow->get($request->query()),
            'workflow' => $workflow->get(array_merge($request->query(), ['per_page' => $perPage])),
            'allworkflow' => Workflow::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->authorize('workflow.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'name' => 'required|string',
            'stagename' => 'required|string', // comma-separated
            'stage' => 'required|string',     // comma-separated
            'active' => 'required|boolean',
        ]);
        $workflow = Workflow::create([
            'name' => $validated['name'],
            'user_id' => Auth::id(),
            'active' => $validated['active'],
        ]);
        $stageNames = explode(',', $validated['stagename']);
        $stages = explode(',', $validated['stage']);

        foreach ($stageNames as $index => $stageName) {
            WorkflowStage::create([
                'workflow_id' => $workflow->id,
                'stagename' => $stageName,
                'stage' => $stages[$index] ?? null,
            ]);
        }
        return redirect()->route('workflow.index')->with('success', 'Workflow Create successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($workflow)
    {
        try {
            $this->authorize('workflow.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $workflow = Workflow::with('stages')->findOrFail($workflow);
        return response()->json([
            'data' => [
                'id'        => $workflow->id,
                'name'      => $workflow->name,
                'stagename' => $workflow->stages->pluck('stagename')->implode(','), // "one,two,three"
                'stage'     => $workflow->stages->pluck('stage')->implode(','),     // "1,2,3"
                'active'    => $workflow->active,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $this->authorize('workflow.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $workflow = Workflow::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'stagename' => 'required|string', // comma-separated
            'stage' => 'required|string',     // comma-separated
            'active' => 'required|boolean',
        ]);
        $workflow->update([
            'name'   => $validated['name'],
            'active' => $validated['active'],
        ]);
        $workflow->stages()->delete();
        $stageNames = explode(',', $validated['stagename']);
        $stages = explode(',', $validated['stage']);

        foreach ($stageNames as $index => $stageName) {
            WorkflowStage::create([
                'workflow_id' => $workflow->id,
                'stagename'   => $stageName,
                'stage'       => $stages[$index] ?? null,
            ]);
        }
        return redirect()->route('workflow.index')->with('success', 'Workflow updated successfully.');
    }



    public function updateStatus(Request $request, $workflow)
    {
        try {
            $this->authorize('workflow.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $workflow = Workflow::findOrFail($workflow);
        $updated = $workflow->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('workflow.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }
}
