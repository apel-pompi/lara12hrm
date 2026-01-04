<?php

namespace App\Services\Accounts;

use App\Filters\Accounts\VoucherFillter;
use App\Models\Accounts\Voucherheader;

class receiptVoucherService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Voucherheader::with(['branch', 'user'])->whereRaw("LEFT(vouchernumber, 4) = 'RCV-'")->orderBY('id', 'DESC');
        $receive = resolve(VoucherFillter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $receive;
    }
}
