<?php

namespace App\Services;

use App\Filters\SalaryTypeFilter;
use App\Models\HRM\SalaryType;

class SalaryTypeService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = SalaryType::with(['branch','user'])->orderBy('id', 'DESC');
        $workhour = resolve(SalaryTypeFilter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $workhour;
    }
}
