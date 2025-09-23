<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Student\Student;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Country;

use App\Models\Student\StudentSource;
use App\Models\Student\StudentStage;
use App\Models\User;
use App\Services\Agency\Student as AgencyStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AgencyStudent $student)
    {
        return Inertia::render('allpages/Agency/Student/student', [
            'student' => $student->get($request->query()),
            'filters'   => $student->get($request->query()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('allpages/Agency/Student/studentcreate', [
            'student' => Student::orderBy('id', 'desc')->get(),
            'countries' => Country::where('status', 1)->get(['id', 'name', 'iso3', 'phonecode', 'currency', 'currency_symbol']),
            'studentstage' => StudentStage::where('active', 1)->get(['id', 'name']),
            'users' => User::get(['id', 'name']),
            'source' => StudentSource::where('active', 1)->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        $validated['status'] = $request->input('status', 0);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $file_name = time() . '_' . $file->getClientOriginalName();
            // Save to storage/app/public/student
            $file->storeAs('student', $file_name, 'public');
            $validated['photo'] = $file_name;
        }



        // // Create Partner
        Student::create([
            // Basic Info
            'student_id'      => $validated['student_id'] ?? null,
            'fname'           => $validated['fname'],
            'lname'           => $validated['lname'],
            'dateofbirth'     => $validated['dateofbirth'] ?? null,
            'gender'          => $validated['gender'] ?? null,
            'email'           => $validated['email'] ?? null,
            'phone'           => $validated['phone'] ?? null,
            'contactpre'      => $validated['contactpre'] ?? null,

            // Address Info
            'preaddcountry'   => $validated['preaddcountry'] ?? null,
            'preaddstate'     => $validated['preaddstate'] ?? null,
            'preaddcity'      => $validated['preaddcity'] ?? null,
            'paddress'        => $validated['paddress'] ?? null,

            // Passport & Visa Info
            'pascountry'      => $validated['pascountry'] ?? null,
            'pasnocountry'    => $validated['pasnocountry'] ?? null,
            'passportno'      => $validated['passportno'] ?? null,
            'visatype'        => $validated['visatype'] ?? null,
            'visaexdate'      => $validated['visaexdate'] ?? null,
            'pvisades'        => $validated['pvisades'] ?? null,

            // Student Preferences
            'intakedate'      => $validated['intakedate'] ?? null,
            'descountry_id'   => $validated['descountry_id'],
            'stage_id'        => $validated['stage_id'] ?? null,
            'metting_note'    => $validated['metting_note'] ?? null,

            //Photo
            'photo'          => $validated['photo'] ?? null,
            // Relations
            'assain_user'     => $validated['assain_user'],
            'source_id'       => $validated['source_id'],
            'user_id'         => Auth::id(),

            // Status
            'status'          => $validated['status'] ?? null,
        ]);

        return redirect()
            ->route('student.index')
            ->with('success', 'Stusent created successfully.');
    }

    /**
     * Display the specified resource.
     */
    
    
    // public function documents(Student $student){
    //     return Inertia::render('allpages/Agency/Student/documents', [
    //         'student' => $student
    //     ]);
    // }
    // public function appoinments(Student $student){
    //     return Inertia::render('allpages/Agency/Student/appoinments', [
    //         'student' => $student
    //     ]);
    // }
    // public function tasks(Student $student){
    //     return Inertia::render('allpages/Agency/Student/tasks', [
    //         'student' => $student
    //     ]);
    // }
    // public function education(Student $student){
    //     return Inertia::render('allpages/Agency/Student/education', [
    //         'student' => $student
    //     ]);
    // }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }

}
