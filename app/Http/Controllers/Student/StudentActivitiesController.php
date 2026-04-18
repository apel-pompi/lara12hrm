<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Default\ApprovalRequest;
use App\Models\Default\Country;
use App\Models\Student\StudentActivities;
use App\Models\Default\Transaction;
use App\Models\Student\Student;
use App\Models\Student\StudentApplication;
use App\Models\Student\StudentInService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentActivitiesController extends Controller
{
    public function index(Student $student)
    {
        $student->load('assainuser');

        return Inertia::render('allpages/Agency/Student/activites', [
            'student' => $student,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get(),
            'activity' => StudentActivities::with(['user'])->orderBy('id', 'DESC')->where('student_id', $student->id)->paginate(10),
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

        ApprovalRequest::where('reference_id', $request->student_id)
            ->update(['status' => 1]);

        return back()->with('message', $request->status == 4
            ? 'Student archived successfully.'
            : 'Student restored successfully.');
    }

    public function confirmTransfer(Request $request)
    {

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'user_id' => 'required',
        ]);

        Student::where('id', $request->student_id)
            ->update(['assain_user' => $request->user_id]);

        ApprovalRequest::where('reference_id', $request->student_id)
            ->update(['status' => 1]);
        // Record activity
        StudentActivities::create([
            'student_id'    => $request->student_id,
            'title'         => "has confirm lead transfer",
            'fristactivity' => null,
            'lastactivity'  => null,
            'user_id'       => Auth::id(),
        ]);

        return back()->with('success', 'Student Transfer successfully');
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
        // Validate user first
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Check existing application
        if (StudentApplication::where('student_id', $student->id)->exists()) {
            return back()->with('error', 'Unable to generate Student ID. Student application is exists.');
        }

        // Get new Student ID
        $studentID = $this->GetStudentID();
        if (!$studentID) {
            return back()->with('error', 'Unable to generate Student ID.');
        }

        // Update student
        $student->update([
            'student_id'  => $studentID,
            'assain_user' => $validated['user_id'],
            'status'      => 2,
        ]);

        // Update lastnumber in transactions table
        if ($transaction = Transaction::where('name', 'Student ID')->where('active', 1)->first()) {
            $numericPart = preg_replace('/\D/', '', $studentID); // digits only
            $transaction->update(['lastnumber' => (int) $numericPart]);
        }

        // Record activity
        StudentActivities::create([
            'student_id'    => $student->id,
            'title'         => "has created student ID",
            'fristactivity' => null,
            'lastactivity'  => null,
            'user_id'       => Auth::id(),
        ]);

        return back()->with('success', 'Student ID generated and assigned successfully.');
    }

    public function confirmonBoard(Request $request)
    {
       
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required',
        ]);

        Student::where('id', $request->student_id)
            ->update(['status' => $request->status]);

        ApprovalRequest::where('reference_id', $request->student_id)
            ->update(['status' => 1]);
        
        // Record activity
        StudentActivities::create([
            'student_id'    => $request->student_id,
            'title'         => "has confirm student onBoard",
            'fristactivity' => null,
            'lastactivity'  => null,
            'user_id'       => Auth::id(),
        ]);
        return back()->with('success', 'Student onBoard confirm successfully');
    }
}
