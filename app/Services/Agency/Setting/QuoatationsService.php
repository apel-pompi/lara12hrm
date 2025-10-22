<?php

namespace App\Services\Agency\Setting;

use App\Filters\Agency\Setting\QuoatationsFilter;
use App\Models\AgencySetting\Quoatations;

class QuoatationsService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Quoatations::with('user')->orderBy('id', 'DESC');

        $student = resolve(QuoatationsFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
