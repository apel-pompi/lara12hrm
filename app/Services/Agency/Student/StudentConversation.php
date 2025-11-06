<?php

namespace App\Services\Agency\Student;

use App\Filters\Agency\Student\ActivityFilter;
use App\Models\Student\StudentUtility;

class StudentConversation
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = StudentUtility::with(['user'])
            ->where('name', 'conversations')
            ->orderBy('id', 'DESC');

        if (!empty($queryParams['id'])) {
            $queryBuilder->where('id', $queryParams['id']);
        }

        if (!empty($queryParams['student_id'])) {
            $queryBuilder->where('student_id', $queryParams['student_id']);
        }

        $student = resolve(ActivityFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);
        return $student;
    }
}
