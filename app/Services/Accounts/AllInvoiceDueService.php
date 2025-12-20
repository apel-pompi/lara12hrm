<?php

namespace App\Services\Accounts;

use App\Filters\Accounts\AllInvoiceFiltter;
use App\Models\Student\StudentInvoiceHD;
use Illuminate\Support\Facades\DB;

class AllInvoiceDueService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = StudentInvoiceHD::with(['student','user'])->withSum('details', 'amount')->where('status', 'Confirmed')->whereIn(DB::raw("LEFT(insnumber, 4)"), ['INV-', 'SR--'])->orderBy('id', 'DESC')->having('details_sum_amount', '>', 0);
        $query = resolve(AllInvoiceFiltter::class)->getResults([
            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $query;
    }
}
