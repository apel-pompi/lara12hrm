<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Default\ApprovalRequest;
use App\Models\Student\Student;
use App\Models\Student\StudentActivities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalRequestController extends Controller
{
    public function studentArchive(Student $student, Request $request)
    {

        if (ApprovalRequest::where('reference_id', $student->id)->exists()) {
            return back()->with('error', 'Archive request is allready exits');
        }

        $created = ApprovalRequest::create([
            'reference_id' => $student->id,
            'description' => $request->details,
            'remarks' => 'Archive',
            'status' => null,
            'user_id' => Auth::id()
        ]);
        if ($created) {
            StudentActivities::create([
                'student_id'    => $student->id,
                'title'         => "has request student archive",
                'fristactivity' => null,
                'lastactivity'  => null,
                'user_id'       => Auth::id(),
            ]);
        }
        return back()->with('success', 'Archive request send successfully.');
    }
}
