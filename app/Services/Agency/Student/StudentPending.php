<?php

namespace App\Services\Agency\Student;

use App\Filters\Agency\StudentFilter;
use App\Models\Student\Student as ModelsStudent;
use App\Models\Student\StudentUtility;
use Illuminate\Support\Facades\Auth;

class StudentPending
{
    public function get(array $queryParams = [])
    {
        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        $queryBuilder = ModelsStudent::with(['user:id,name', 'assainuser:id,name', 'source:id,name', 'country:id,name'])->where('status',null)->orderBy('id', 'DESC');

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
