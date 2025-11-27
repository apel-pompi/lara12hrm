<?php

namespace App\Services;

use App\Filters\WorkHourFilter;
use App\Models\HRM\WorkHourSetup;

class WorkHours
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = WorkHourSetup::with(['branch','user'])->orderBy('id', 'DESC');
        $workhour = resolve(WorkHourFilter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $workhour;
    }
}
