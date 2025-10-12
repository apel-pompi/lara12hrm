<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Student\Student;
use App\Models\Student\StudentInService;
use Inertia\Inertia;

class StudentTasks extends Controller
{
    public function index(Student $student)
    {
        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/tasks', [
            'student' => $student,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get()
        ]);
    }
}
