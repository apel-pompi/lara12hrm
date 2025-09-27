<?php

namespace App\Services\Agency\Setting;

use App\Filters\Agency\Setting\AcademicFilter;
use App\Models\Default\Academic;

class AcademicService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Academic::with('user')->orderBy('id', 'DESC');

        $student = resolve(AcademicFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
