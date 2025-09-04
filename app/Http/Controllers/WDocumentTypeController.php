<?php

namespace App\Http\Controllers;

use App\Models\WDocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Inertia\Inertia;

class WDocumentTypeController extends Controller
{
    public function index()
    {

        return Inertia::render('allpages/Agency/documenttype', [
            'documenttype' => WDocumentType::with(['user'])->orderBy('id', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {
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
