<?php

namespace App\Services\Agency\Student;

use App\Filters\Agency\StudentFilter;
use App\Models\Student\Student as ModelsStudent;
use Illuminate\Support\Facades\Auth;

class StudentOnBoard
{
    public function get(array $queryParams = [])
    {
        $user = Auth::user();
        $roles = $user->getRoleNames();
        $queryBuilder = ModelsStudent::with(['user:id,name', 'assainuser:id,name', 'source:id,name', 'country:id,name'])->where('status',3)
            ->orderBy('student_id', 'DESC');

        if (! $roles->intersect(['superadmin', 'Admin', 'Manager'])->count()) {
            $queryBuilder->where('assain_user', Auth::id());
        }

        $students = resolve(StudentFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams,
        ]);

        return $students;

    }
}
