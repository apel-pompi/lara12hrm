<?php

namespace App\Filters\Accounts;

use App\Filters\BaseFilter;
use App\Filters\Components\Default\Branch;
use App\Filters\Components\Default\Name;

class SupplierFillter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            Name::class,
            Branch::class,
        ];
    }
}
