<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\VwUnPaidInv;
use App\Models\HRM\Branch;
use App\Services\Accounts\SupplierPayableService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SupplierPayaleController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, SupplierPayableService $payable_service)
    {
        try {
            $this->authorize('SupplierPayable.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/accounts/supplier/payable', [
            'filters'   => $payable_service->get($request->query()),
            'payables' => $payable_service->get(array_merge($request->query(), ['per_page' => 15])),
            'branch' => Branch::all(),
        ]);
    }
}
