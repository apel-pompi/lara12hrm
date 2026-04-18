<?php

namespace App\Services\Accounts;

use App\Filters\Accounts\AllInvoiceFiltter;
use App\Models\Student\StudentInvoiceHD;
use Illuminate\Support\Facades\DB;

class AllMoneyReceiptService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = StudentInvoiceHD::with(['student','user'])->withSum('details', 'amount')->whereIn(DB::raw("LEFT(insnumber, 4)"), ['MR--'])->orderBy('id', 'DESC');
        $query = resolve(AllInvoiceFiltter::class)->getResults([
            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $query;
    }
}
