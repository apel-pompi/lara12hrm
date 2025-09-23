<?php

namespace App\Services\Agency\Setting;

use App\Filters\Agency\Setting\FeesFilter;
use App\Models\Default\Fees;

class FeesService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Fees::with('user')->orderBy('id', 'DESC');

        $student = resolve(FeesFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
