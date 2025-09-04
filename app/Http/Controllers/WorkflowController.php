<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use App\Models\WorkflowStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WorkflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('allpages/Agency/Setting/workflow', [
            'workflow' => Workflow::with(['user'])->orderBy('id', 'desc')->get()
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
    public function store(Request $request)
    {
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
     * Display the specified resource.
     */
    public function show(Workflow $workflow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($workflow)
    {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workflow $workflow)
    {
        //
    }

    public function updateStatus(Request $request, $workflow)
    {
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
