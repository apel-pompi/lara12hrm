<?php

namespace App\Services\Accounts;

use App\Filters\Accounts\ChartOfAccountsFillter;
use App\Models\Accounts\ChartOfAccount;

class ChartOfAccountService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = ChartOfAccount::with(['GroupOne','GroupTwo','GroupThree','user'])->orderBy('id', 'DESC');
        $workhour = resolve(ChartOfAccountsFillter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $workhour;
    }
}
