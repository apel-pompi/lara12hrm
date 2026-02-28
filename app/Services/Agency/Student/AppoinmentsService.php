<?php

namespace App\Services\Agency\Student;

use App\Filters\Agency\Student\ActivityFilter;
use App\Models\Student\StudentUtility;
use Illuminate\Support\Facades\Auth;

class AppoinmentsService
{
    public function get(array $queryParams = [])
    {
        $user  = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();

        $query = StudentUtility::with(['user', 'student'])
            ->latest();

        // Restrict non-admin users
        if (! $roles->intersect(['superadmin', 'Admin', 'Manager'])->isNotEmpty()) {
            $query->where('user_id', $user->id);
        }

        if (!empty($queryParams['id'])) {
            $query->where('id', $queryParams['id']);
        }

        if (!empty($queryParams['student_id'])) {
            $query->where('student_id', $queryParams['student_id']);
        }

        return resolve(ActivityFilter::class)->getResults([
            'builder' => $query,
            'params'  => $queryParams,
        ]);
    }
}
