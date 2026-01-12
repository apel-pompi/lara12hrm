<?php

namespace App\Services\Accounts;

use App\Filters\Accounts\PaymentFiltter;
use App\Models\Accounts\Voucherheader;

class SupplierManagePayamentService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Voucherheader::with([
            'branch',
            'voucherdt.subacccode',
            'voucherdt' => function ($q) {
                $q->whereNotNull('subacccode')
                    ->where('primeamt', '>', 0);
            }
        ])
            ->whereRaw("LEFT(vouchernumber, 4) = 'APV-'")
            ->whereHas('voucherdt', function ($q) {
                $q->whereNotNull('subacccode')
                    ->where('primeamt', '>', 0);
            })->orderBy('vouchernumber', 'desc');

        $payment = resolve(PaymentFiltter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $payment;
    }
}
