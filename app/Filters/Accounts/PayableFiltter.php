<?php

namespace App\Filters\Accounts;

use App\Filters\BaseFilter;
use App\Filters\Components\Accounts\ContactPerson;
use App\Filters\Components\Accounts\SupplierID;
use App\Filters\Components\Default\Branch;


class PayableFiltter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
           SupplierID::class,
           Branch::class,
           ContactPerson::class,
        ];
    }
}
