<?php

namespace App\Services\Agency;

use App\Filters\Agency\StudentFilter;
use App\Models\Student\Student as ModelsStudent;

class Student
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = ModelsStudent::with(['user','assainuser','source','country'])->orderBy('id', 'DESC');

        $student = resolve(StudentFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
