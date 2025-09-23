<?php

namespace App\Services\Agency\Setting;

use App\Filters\Agency\Setting\StudentSourceFilter;
use App\Models\Student\StudentSource;

class StudentSourceService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = StudentSource::with('user')->orderBy('id', 'DESC');

        $student = resolve(StudentSourceFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
