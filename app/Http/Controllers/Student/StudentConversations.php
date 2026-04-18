<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentInService;
use App\Models\Student\StudentUtility;
use App\Services\Agency\Student\StudentConversation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Inertia\Inertia;

class StudentConversations extends Controller
{
    public function index(Student $student, StudentConversation $service, Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/conversations', [
            'student' => $student,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get(),
            'conversation' => $service->get(array_merge($request->query(), [
                'per_page' => $perPage,
                'student_id' => $student->id,
            ])),
            'filters'   => $service->get($request->query()),
        ]);
    }


    public function store(Student $student, Request $request)
    {
        $request->validate([
            'discus' => 'required|string|max:1000',
        ]);
        $created = StudentUtility::create([
            'name' => 'conversations',
            'datetime' => Carbon::now(),
            'discus' => $request->discus,
            'student_id' => $student->id,
            'user_id' => Auth::id(),
        ]);
        if ($created) {
            StudentActivities::create([
                'student_id' => $student->id,
                'title' => "has started student conversations",
                'fristactivity' => null,
                'lastactivity' => null,
                'user_id' => Auth::id()
            ]);
            if (is_null($student->status)) {

                $student->update([
                    'status' => 1
                ]);

                return back()->with('message', 'Conversation created successfully!');
            }
        } else {
            return back()->with('error', 'Unable to storage');
        }
    }

    public function fetchData($conversation)
    {
        $fechData = StudentUtility::with('user', 'student')->where('student_id', $conversation)->where('name', 'conversations')->get();
        return response()->json([
            'success' => true,
            'data' => $fechData,
        ]);
        return back()->with('message', 'Conversation view successfully!');
    }
}
