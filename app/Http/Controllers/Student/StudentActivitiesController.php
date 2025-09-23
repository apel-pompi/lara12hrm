<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentActivities;
use App\Http\Requests\StudentActivities\StoreStudentActivitiesRequest;
use App\Http\Requests\StudentActivities\UpdateStudentActivitiesRequest;
use App\Models\Student\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentActivitiesController extends Controller
{
    public function index(Student $student){
        return Inertia::render('allpages/Agency/Student/activites', [
            'student' => $student,
            'activity' => StudentActivities::where('student_id',$student->id)->get()
        ]);
    }

    public function updateRate(Request $request)
    {
        StudentActivities::create([
            'student_id' => $request->student_id,
            'title' => "has changed student's rating from",
            'fristactivity' => $request->status,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);
    }
}
