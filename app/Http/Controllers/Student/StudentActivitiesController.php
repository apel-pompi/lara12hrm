<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentActivities;
use App\Models\Default\Transaction;
use App\Models\Student\Student;
use App\Models\Student\StudentInService;
use App\Models\User;
use App\Services\Agency\Student\StudentActivity;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentActivitiesController extends Controller
{
    public function index(Student $student, Request $request, StudentActivity $service)
    {
        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/activites', [
            'student' => $student,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get(),
            'activity' => $service->get($request->query()),
            'filters'   => $service->get($request->query()),
        ]);
    }

    public function updateArchive(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:1,4',
        ]);

        Student::where('id', $request->student_id)
            ->update(['status' => $request->status]);

        return back()->with('message', $request->status == 4
            ? 'Student archived successfully.'
            : 'Student restored successfully.');
    }

    public function updateRate(Request $request)
    {

        $rating = '';
        if ($request->status == 1) {
            $rating = "has changed student's rating lost";
        } elseif ($request->status == 2) {
            $rating = "has changed student's rating cold";
        } elseif ($request->status == 3) {
            $rating = "has changed student's rating warm";
        } elseif ($request->status == 4) {
            $rating = "has changed student's rating hot";
        } else {
            $rating = "has changed student's no rating";
        }
        StudentActivities::create([
            'student_id' => $request->student_id,
            'title' => $rating,
            'fristactivity' => $request->status,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);
    }

    private function GetStudentID()
    {
        $transaction = Transaction::where('name', 'Student ID')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    public function updateAssignee(Request $request, Student $student)
    {
        $studentID = $this->GetStudentID();

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

        $numericPart = (int) preg_replace('/[^0-9]/', '', $studentID);
        $transaction = Transaction::where('name', 'Student ID')->where('active', 1)->first();
        if ($transaction) {
            $transaction->update(['lastnumber' => $numericPart]);
        }
        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has created student ID",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);
        return back()->with('error', 'Unable to generate Student ID.');
    }
}
