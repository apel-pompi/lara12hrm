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
        $queryBuilder = '';
        if ($roles->contains('superadmin')) {
            $queryBuilder = ModelsStudent::with(['user', 'assainuser', 'source', 'country'])->where('status',3)->orderBy('id', 'DESC');
        }elseif($roles->contains('Admin')){
            $queryBuilder = ModelsStudent::with(['user', 'assainuser', 'source', 'country'])->where('status',3)->orderBy('id', 'DESC');
        }elseif($roles->contains('Manager')){
            $queryBuilder = ModelsStudent::with(['user', 'assainuser', 'source', 'country'])->where('status',3)->orderBy('id', 'DESC');
        }else{
            $queryBuilder = ModelsStudent::with(['user', 'assainuser', 'source', 'country'])->where('assain_user',Auth::id())->where('status',3)->orderBy('id', 'DESC');
        }

        $student = resolve(StudentFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
