<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Default\TransactionName;
use App\Http\Requests\Default\TransactionName\StoreTransactionNameRequest;
use App\Http\Requests\Default\TransactionName\UpdateTransactionNameRequest;
use App\Services\Default\TransactionNameService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionNameController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TransactionNameService $transaction)
    {

        $this->authorize('TrancactionNo.index');

        return Inertia::render('allpages/default/transactionname',[
            'tranactionFilter' => TransactionName::orderBy('id', 'desc')->get(),
            'tranactionname' => $transaction->get($request->query()),
            'filters'   => $transaction->get($request->query()),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionNameRequest $request)
    {
        $this->authorize('TrancactionNo.store');

        $validated = $request->validated();
        TransactionName::create([
            'name'    => $validated['name'],
            'adddate' => Date('Y-m-d'),
            'user_id' => Auth::id(), // logged-in user
            'active'  => $validated['active'] ?? 0,
        ]);
        return redirect()->route('transactionName.index')->with('success', 'Transaction name Create successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionName $transactionName)
    {
        $this->authorize('TrancactionNo.destroy');

        try {
            $transactionName->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete transaction name.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function updateStatus(Request $request, $transaction)
    {
        $this->authorize('TrancactionNo.updateStatus');

        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $transaction = TransactionName::findOrFail($transaction);
        $updated = $transaction->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('transactionName.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }
}
