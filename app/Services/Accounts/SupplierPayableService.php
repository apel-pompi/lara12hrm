<?php

namespace App\Services\Accounts;

use App\Filters\Accounts\PayableFiltter;
use App\Models\Accounts\VwApayable;

class SupplierPayableService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = VwApayable::with(['ChartOFAccount.GroupThree','branch'])->where('payableamt', '>', 0)->orderBy('suppliercode', 'DESC');
        $payable = resolve(PayableFiltter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $payable;
    }
}
