<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentCheckLog;
use App\Models\Student\StudentInService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentCheckLogController extends Controller
{
    public function index(Student $student)
    {
        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/checkin', [
            'student' => $student,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get(),
            'checkin' => StudentCheckLog::with(['student', 'user'])->orderBy('id', 'DESC')->where('student_id', $student->id)->paginate(15)
        ]);
    }

    public function store(Student $student, Request $request)
    {
        $created = StudentCheckLog::create([
            'student_id' => $student->id,
            'status' => $request->status,
            'user_id' => Auth::id(),
        ]);

        if ($created) {
            $student->update([
                'status'      => 1,
            ]);
            StudentActivities::create([
                'student_id' => $student->id,
                'title' => "has created student check in",
                'fristactivity' => null,
                'lastactivity' => null,
                'user_id' => Auth::id()
            ]);
            return back()->with('success', 'check in successfully.');
        } else {
            return back()->with('error', 'Unable to check in');
        }
    }

    public function checkOut(Student $student, Request $request)
    {
        $created = StudentCheckLog::create([
            'student_id' => $student->id,
            'status' => 'Check OUT',
            'user_id' => Auth::id(),
        ]);

        if ($created) {
            StudentActivities::create([
                'student_id' => $student->id,
                'title' => "has created student check out",
                'fristactivity' => null,
                'lastactivity' => null,
                'user_id' => Auth::id()
            ]);
            return back()->with('success', 'check in successfully.');
        } else {
            return back()->with('error', 'Unable to check in');
        }
    }
}
