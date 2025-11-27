<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Default\Transaction;
use App\Http\Requests\Default\Transaction\StoreTransactionRequest;
use App\Services\Default\TransactionNoService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TransactionNoService $transaction)
    {
        try {
            $this->authorize('Trancaction.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return Inertia::render('allpages/default/transaction',[
            'tranaction' => $transaction->get($request->query()),
            'filters'   => $transaction->get($request->query()),
            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        try {
            $this->authorize('Trancaction.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validated();
        Transaction::create([
            'name'              => $validated['name'],
            'trncode'           => $validated['trncode'],
            'lastnumber'           => $validated['lastnumber'],
            'increment'           => $validated['increment'],
            'adddate' => now(),
            'user_id'           => Auth::id(), // logged-in user
            'active'            => $validated['active'] ?? 0,
        ]);

        return redirect()
            ->route('transaction.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        try {
            $this->authorize('Trancaction.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        try {
            $transaction->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete transaction name.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $transaction)
    {
        try {
            $this->authorize('Trancaction.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $transaction = Transaction::findOrFail($transaction);
        $updated = $transaction->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('transaction.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }

    public function createMonth()
    {
        $a = array();
        for ($i = 1; $i <= 12; $i++) {
            $a[$i] = date("F", mktime(0, 0, 0, $i, $i));
        }
        return $a;
    }

    public function createYear()
    {
        $a = array();
        for ($i = date('Y'); $i >= date('Y') - 5; $i--) {
            $a[$i] = $i;
        }
        return $a;
    }
}
