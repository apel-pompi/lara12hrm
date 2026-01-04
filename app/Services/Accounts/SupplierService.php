<?php

namespace App\Services\Accounts;

use App\Filters\Accounts\SupplierFillter;
use App\Models\Accounts\Supplier;

class SupplierService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Supplier::with(['user'])->orderBY('id', 'DESC');
        $receive = resolve(SupplierFillter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $receive;
    }
}
