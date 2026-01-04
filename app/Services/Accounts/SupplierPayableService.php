<?php

namespace App\Services\Accounts;

use App\Filters\Accounts\PayableFiltter;
use App\Models\Accounts\VwUnPaidInv;

class SupplierPayableService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = VwUnPaidInv::orderBy('suppliercode', 'DESC');
        $payable = resolve(PayableFiltter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $payable;
    }
}
