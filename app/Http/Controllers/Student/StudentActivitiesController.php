<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentActivities;
use App\Http\Requests\StudentActivities\StoreStudentActivitiesRequest;
use App\Http\Requests\StudentActivities\UpdateStudentActivitiesRequest;
use App\Models\Default\Transaction;
use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentActivitiesController extends Controller
{
    public function index(Student $student)
    {
        return Inertia::render('allpages/Agency/Student/activites', [
            'student' => $student,
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

    public function updateAssignee(Request $request, Student $student)
    {
        // Transaction fetch
        $tranno = Transaction::with('transactionname')
            ->whereHas('transactionname', function ($query) {
                $query->where('name', 'Student ID');
            })
            ->where('yearname', date('y'))   // 2-digit year
            ->where('monthname', date('n')) // month without leading zero
            ->where('active', 1)
            ->first(['id', 'lastnumber', 'increment', 'trncode']);

        $studentID = '';

        if ($tranno) {
            $lastnumber = $tranno->lastnumber; // previous last number
            $increment  = $tranno->increment;  // increment step
            $trncode    = $tranno->trncode;    // SIDHA2510

            // new Student ID generate
            $newNumber = $lastnumber + $increment;
            $studentID = $trncode . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

            // Transaction lastnumber update
            $tranno->update([
                'lastnumber' => $newNumber
            ]);
        }

        if ($studentID) {
            // Validate user_id
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            // Student update
            $student->update([
                'student_id'   => $studentID,
                'assain_user'  => $validated['user_id'],
                'status'       => '2',
            ]);
        }

        return back()->with('error', 'Unable to generate Student ID.');
    }
}
