<?php

namespace App\Services\Agency\Student;

use App\Filters\Agency\Student\ActivityFilter;
use App\Models\Student\StudentActivities;

class StudentActivity
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = StudentActivities::with(['user'])->orderBy('id', 'DESC');

        $student = resolve(ActivityFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
