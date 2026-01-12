<?php

namespace App\Filters\Accounts;

use App\Filters\BaseFilter;
use App\Filters\Components\Accounts\ContactPerson;
use App\Filters\Components\Accounts\SubAccCode;
use App\Filters\Components\Accounts\VoucherDate;
use App\Filters\Components\Accounts\VoucherNo;
use App\Filters\Components\Default\Branch;
use App\Filters\Components\Default\Month;
use App\Filters\Components\Default\Status;
use App\Filters\Components\Default\Year;

class PaymentFiltter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
           SubAccCode::class,
           VoucherNo::class,    
           Branch::class,
           VoucherDate::class,
           Year::class,
           Month::class,
           Status::class,
        ];
    }
}
