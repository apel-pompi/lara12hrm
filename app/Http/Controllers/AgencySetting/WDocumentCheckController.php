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
            $this->authorize('DocumentCheck.index');
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
            $this->authorize('DocumentCheck.store');
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

    public function adddoctypeEdit($id)
    {
        try {
            $this->authorize('DocumentCheck.edit');
        } catch (AuthorizationException $e) {
            return response()->json([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ], 403);
        }


        $documenttype = WDocumentType::with('docusage')->findOrFail($id);

        return response()->json([
            'data' => [
                'id'      => $documenttype->id,
                'docname' => $documenttype->docname,
                'active'  => $documenttype->active,
                'docusage' => $documenttype->docusage,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $this->authorize('DocumentCheck.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'doctype_id' => 'required|numeric',
        ]);

        $documenttype = WDocumentCheck::where('doctype_id', $id)->firstOrFail();
        $documenttype->update([
            'doctype_id' => $validated['doctype_id'],
        ]);

        return redirect()->route('documentlist.index', $documenttype->workflow_id)->with('success', 'Document List updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $this->authorize('DocumentCheck.destroy');
        } catch (AuthorizationException $e) {
            return back()->with('error', 'You are not authorized to delete this item.');
        }

        try {
            $documentcheck = WDocumentCheck::findOrFail($id);
            $workflow_id = $documentcheck->workflow_id;

            $documentcheck->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete document checklist.');
        }

        return redirect()
            ->route('documentlist.index', $workflow_id)
            ->with('success', 'Document Checklist deleted successfully.');
    }
}
