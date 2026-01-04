<?php

namespace App\Filters\Accounts;

use App\Filters\BaseFilter;
use App\Filters\Components\Accounts\Referance;
use App\Filters\Components\Accounts\VoucherDate;
use App\Filters\Components\Accounts\VoucherNo;
use App\Filters\Components\Default\Branch;
use App\Filters\Components\Default\Month;
use App\Filters\Components\Default\Status;
use App\Filters\Components\Default\Year;

class VoucherFillter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            VoucherNo::class,
            VoucherDate::class,
            Branch::class,
            Referance::class,
            Year::class,
            Month::class,
            Status::class
        ];
    }
}
