<?php

namespace App\Services;

use App\Filters\LeaveFilter;
use App\Models\HRM\Leave;
use Illuminate\Support\Facades\Auth;

class LevaveService
{
    public function get(array $queryParams = [])
    {
        $user  = Auth::user();
        $roles = $user->getRoleNames();

        $queryBuilder = Leave::with([
            'employee',
            'substituteEmployee',
            'leavePlan'
        ])->orderBy('fromdate', 'desc');
        
        if (! $roles->intersect(['superadmin', 'Admin', 'Manager'])->isNotEmpty()) {
            $queryBuilder->whereHas('employee', function ($q) use ($user) {
                $q->where('empname', $user->name);
            });
        }
        
        $leave = resolve(LeaveFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $leave;
    }
}
