<?php

namespace App\Filters\Accounts;

use App\Filters\BaseFilter;
use App\Filters\Components\Accounts\InsDate;
use App\Filters\Components\Accounts\InsNumber;
use App\Filters\Components\Accounts\InsRefCode;
use App\Filters\Components\Accounts\StudentID;
use App\Filters\Components\Accounts\StudentName;
use App\Filters\Components\Accounts\StudentPhone;

class AllInvoiceFiltter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            InsNumber::class,
            InsRefCode::class,
            InsDate::class,
            StudentID::class,
            StudentName::class,
            StudentPhone::class,
        ];
    }
}
