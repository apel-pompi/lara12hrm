<?php

namespace App\Http\Controllers\Default;
use App\Http\Controllers\Controller;
use App\Models\Default\Transaction;
use App\Http\Requests\Default\Transaction\StoreTransactionRequest;
use App\Http\Requests\Default\Transaction\UpdateTransactionRequest;
use App\Models\Branch;
use App\Models\Default\TransactionName;
use App\Services\Default\TransactionNoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TransactionNoService $transaction)
    {
        return Inertia::render('allpages/default/transaction',[
            'tranaction' => $transaction->get($request->query()),
            'filters'   => $transaction->get($request->query()),
            'tranactionName' => TransactionName::where('active',1)->get(['id','name']),
            'branch' => Branch::where('active',1)->get(['id','branchname']),
            'months' => collect($this->createMonth())
                ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                ->values()
                ->toArray(),
            'years' => collect($this->createYear())
                ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                ->values()
                ->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();
        $year = $validated['yearname'];
        $newyear = substr($year, -2);
        Transaction::create([
            'trnname_id'              => $validated['trnname_id'],
            'trncode'           => $validated['trncode'],
            'branch_id'           => $validated['branch_id'],
            'yearname'           => $newyear,
            'monthname'           => $validated['monthname'],
            'lastnumber'           => $validated['lastnumber'],
            'increment'           => $validated['increment'],
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
