<?php

namespace App\Services\Accounts;

use App\Filters\Accounts\VoucherFillter;
use App\Models\Accounts\Voucherheader;

class reverseVoucherService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Voucherheader::with(['branch', 'user'])->whereRaw("LEFT(vouchernumber, 4) = 'REV-'")->orderBY('id', 'DESC');
        $reverse = resolve(VoucherFillter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $reverse;
    }
}
