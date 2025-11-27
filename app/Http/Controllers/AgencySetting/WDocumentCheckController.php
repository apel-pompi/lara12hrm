<?php

namespace App\Http\Controllers\AgencySetting;

use App\Http\Controllers\Controller;
use App\Models\AgencySetting\WDocumentCheck;
use App\Models\AgencySetting\WDocumentType;
use App\Models\AgencySetting\Workflow;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WDocumentCheckController extends Controller
{
    use AuthorizesRequests;

    public function index($id)
    {
        try {
            $this->authorize('workflowDocumentCheck.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/Agency/Setting/documentcheck', [
            'documentcheck' => WDocumentCheck::get(),
            'workflow' => Workflow::with(['stages.documentChecks.documenttype'])->where('id', $id)
                ->where('active', 1)->firstOrFail(),
            'documenttype' => WDocumentType::where('active', 1)->get()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('workflowDocumentCheck.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'workflow_id' => 'required|integer',
            'doctype_id' => 'required|integer',
            'workstage_id' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        $documentcheck = WDocumentCheck::create([
            'workflow_id' => $validated['workflow_id'],
            'doctype_id' => $validated['doctype_id'],
            'workstage_id' => $validated['workstage_id'],
            'user_id' => Auth::id(),
            'active' => $validated['active'],
        ]);

        return redirect()->route('documentlist.index', $validated['workflow_id'])->with('success', 'Document List Create successfully.');
    }
}
