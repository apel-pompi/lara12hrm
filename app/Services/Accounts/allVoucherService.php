<?php

namespace App\Services\Accounts;

use App\Filters\Accounts\VoucherFillter;
use App\Models\Accounts\Voucherheader;

class allVoucherService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Voucherheader::with(['branch', 'user'])->orderBY('id', 'DESC');
        $receive = resolve(VoucherFillter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $receive;
    }
}
