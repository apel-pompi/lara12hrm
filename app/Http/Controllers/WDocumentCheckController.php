<?php

namespace App\Http\Controllers;

use App\Models\WDocumentCheck;
use App\Models\WDocumentType;
use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WDocumentCheckController extends Controller
{
    public function index($id)
    {

        return Inertia::render('allpages/Agency/Setting/documentcheck', [
            'documentcheck' => WDocumentCheck::get(),
            'workflow' => Workflow::with(['stages.documentChecks.documenttype'])->where('id', $id)
                ->where('active', 1)->firstOrFail(),
            'documenttype' => WDocumentType::where('active', 1)->get()
        ]);
    }

    public function store(Request $request)
    {

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
