<?php

namespace App\Http\Controllers\AgencySetting;

use App\Http\Controllers\Controller;
use App\Models\AgencySetting\WDocumentType;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WDocumentTypeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        try {
            $this->authorize('workflowDocument.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/Agency/Setting/documenttype', [
            'documenttype' => WDocumentType::with(['user'])->orderBy('id', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('workflowDocument.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'docname' => 'required|string',
            'active' => 'required|boolean',
        ]);

        $documenttype = WDocumentType::create([
            'docname' => $validated['docname'],
            'adddate' => Date('Y-m-d'),
            'totaluse' => '0',
            'user_id' => Auth::id(),
            'active' => $validated['active'],
        ]);

        return redirect()->route('documenttype.index')->with('success', 'Documenttype Create successfully.');
    }

    public function edit($id)
    {
        try {
            $this->authorize('workflowDocument.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $documenttype = WDocumentType::findOrFail($id);

        return response()->json([
            'data' => [
                'id'      => $documenttype->id,
                'docname' => $documenttype->docname,
                'active'  => $documenttype->active,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $this->authorize('workflowDocument.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $documenttype = WDocumentType::findOrFail($id);

        $validated = $request->validate([
            'docname' => 'required|string',
            'active'  => 'required|boolean',
        ]);

        $documenttype->update([
            'docname' => $validated['docname'],
            'active'  => $validated['active'],
        ]);

        return redirect()->route('documenttype.index')->with('success', 'Documenttype updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $this->authorize('workflowDocument.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $documenttype = WDocumentType::findOrFail($id);
        $updated = $documenttype->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('documenttype.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }
}
