<?php

namespace App\Filters\Accounts;

use App\Filters\BaseFilter;
use App\Filters\Components\Accounts\ContactPerson;
use App\Filters\Components\Accounts\SubEmail;
use App\Filters\Components\Accounts\SubPhone;
use App\Filters\Components\Accounts\SupplierAddress;
use App\Filters\Components\Accounts\SupplierID;
use App\Filters\Components\Default\Name;

class SupplierFillter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            Name::class,
            SupplierID::class,
            SupplierAddress::class,
            ContactPerson::class,
            SubPhone::class,
            SubEmail::class,
        ];
    }
}
