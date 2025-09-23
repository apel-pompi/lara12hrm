<?php

namespace App\Filters;

use App\Filters\BaseFilter;
use App\Filters\Components\Default\Branch;
use App\Filters\Components\Default\Year;
use App\Filters\Components\Default\Month;

class HolidayHdFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            Branch::class,
            Year::class,
            Month::class

        ];
    }
}
