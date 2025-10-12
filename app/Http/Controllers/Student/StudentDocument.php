<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Student\Student;
use App\Models\Student\StudentInService;
use Inertia\Inertia;

class StudentDocument extends Controller
{
    public function index(Student $student)
    {
        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/documents', [
            'student' => $student,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get()
        ]);
    }
}
