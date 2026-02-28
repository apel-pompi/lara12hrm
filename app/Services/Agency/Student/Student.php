<?php

namespace App\Services\Agency\Student;

use App\Filters\Agency\StudentFilter;
use App\Models\Student\Student as ModelsStudent;
use Illuminate\Support\Facades\Auth;

class Student
{
    public function get(array $queryParams = [])
    {
        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        $queryBuilder = ModelsStudent::with(['user:id,name', 'assainuser:id,name', 'source:id,name'])
            ->orderBy('id', 'DESC');

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
