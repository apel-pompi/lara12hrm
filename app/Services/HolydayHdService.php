<?php

namespace App\Services;

use App\Filters\HolidayHdFilter;
use App\Models\HolidayHd;


class HolydayHdService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = HolidayHd::with('branch')->orderBy('id', 'DESC');
        $holydayhd = resolve(HolidayHdFilter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $holydayhd;
    }
}
