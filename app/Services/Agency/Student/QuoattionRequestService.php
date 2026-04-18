<?php

namespace App\Services\Agency\Student;

use App\Filters\Agency\Student\ActivityFilter;
use App\Models\Default\ApprovalRequest;
use App\Models\Student\StudentUtility;
use Illuminate\Support\Facades\Auth;

class QuoattionRequestService
{
    public function get(array $queryParams = [])
    {
        $user = Auth::user();

        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $isAdmin = $user->hasAnyRole(['superadmin', 'Admin', 'Manager']);

        $query = ApprovalRequest::with(['user', 'student'])
            ->leftJoin('student_quotation_h_d_s as b', 'approval_requests.description', '=', 'b.id')
            ->where('approval_requests.remarks', 'quotation')
            ->select(
                'approval_requests.*',
                'b.quotation_no',
                'b.totalamount',
                'b.adddate',
                'b.active',
                'b.notes'
            )
            ->orderByDesc('approval_requests.id');

        // Restrict non-admin users
        if (! $isAdmin) {
            $query->where('approval_requests.user_id', $user->id);
        }

        // Filters
        if (!empty($queryParams['id'])) {
            $query->where('approval_requests.id', $queryParams['id']);
        }

        if (!empty($queryParams['student_id'])) {
            $query->where('approval_requests.student_id', $queryParams['student_id']);
        }

        return resolve(ActivityFilter::class)->getResults([
            'builder' => $query,
            'params'  => $queryParams,
        ]);
    }
}
