<?php

namespace App\Services\Agency\Setting;

use App\Filters\Agency\Setting\StudentStageFilter;
use App\Models\Student\StudentStage;

class StudentStageService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = StudentStage::with('user')->orderBy('id', 'DESC');

        $student = resolve(StudentStageFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
