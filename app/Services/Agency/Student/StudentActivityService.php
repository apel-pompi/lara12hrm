<?php

namespace App\Services\Agency\Student;

use App\Filters\Agency\Student\ActivityFilter;
use App\Models\Student\StudentActivities;

class StudentActivityService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = StudentActivities::with(['user'])->orderBy('id', 'DESC');
        if (isset($queryParams['id'])) {
            $queryBuilder->where('id', $queryParams['id']);
        }

        $student = resolve(ActivityFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
