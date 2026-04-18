<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Default\Notification;
use App\Models\Student\Student;
use App\Models\Student\StudentInService;
use App\Models\Student\StudentUtility;
use App\Models\Student\StudentActivities;
use App\Services\Agency\Student\AppoinmentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Inertia\Inertia;

class StudentAppointements extends Controller
{
    public function index(Student $student, Request $request, AppoinmentsService $service)
    {
        $student->load('assainuser');
        $perPage = $request->query('per_page', 10);

        return Inertia::render('allpages/Agency/Student/appoinments', [
            'student' => $student,
            'studentService' => StudentInService::with(['productfees'])->where('student_id', $student->id)->get(),
            'appoiment' => $service->get(array_merge($request->query(), [
                'per_page' => $perPage,
                'student_id' => $student->id,
            ])),
            'filters'   => $service->get($request->query()),
        ]);
    }

    public function store(Student $student, Request $request)
    {

        $request->validate([
            'apdate' => 'required|string',
            'discus' => 'required|string|max:1000',
        ]);
        $datetime = Carbon::createFromFormat(
            'm/d/Y, h:i:s A',
            $request->apdate
        )->format('Y-m-d H:i:s');

        if (is_null($student->status)) {

            $student->update([
                'status' => 1
            ]);
        }


        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Appoinment',
            'message' => $request->discus,
            'type' => 'info',
            'action_url' => '/student/activities/' . $student->id . '/appoinments',
            'read' => false
        ]);
        $created = StudentUtility::create([
            'name' => 'appoinments',
            'datetime' => $datetime,
            'discus' => $request->discus,
            'student_id' => $student->id,
            'user_id' => Auth::id(),
        ]);
        if ($created) {
            StudentActivities::create([
                'student_id' => $student->id,
                'title' => "has created student appoinments",
                'fristactivity' => null,
                'lastactivity' => null,
                'user_id' => Auth::id()
            ]);
            return back()->with('message', 'Appoinments created successfully!');
        } else {
            return back()->with('error', 'Unable to storage');
        }
    }
}
