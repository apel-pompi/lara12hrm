<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Student\Student;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Default\Country;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentSource;
use App\Models\Student\StudentStage;
use App\Models\User;
use App\Models\Student\StudentUtility;
use App\Services\Agency\Student\Student as AgencyStudent;
use App\Services\Agency\Student\StudentArchive;
use App\Services\Agency\Student\StudentLead;
use App\Services\Agency\Student\StudentPending;
use App\Services\Agency\Student\StudentOnBoard;
use App\Services\Agency\Student\StudentProspect;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;


class StudentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AgencyStudent $student)
    {
        try {
            $this->authorize('Student.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $perPage = $request->query('per_page', 10);


        $user = Auth::user();
        if ($user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {

            return Inertia::render('allpages/Agency/Student/student', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::count(),
                'countPending' => Student::where('status', null)->count(),
                'countLead' => Student::where('status', 1)->count(),
                'countProspect' => Student::where('status', 2)->count(),
                'countonBoard' => Student::where('status', 3)->count(),
                'countArchive' => Student::where('status', 4)->count(),
            ]);
        } else {

            return Inertia::render('allpages/Agency/Student/student', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::where('assain_user', Auth::id())->count(),
                'countPending' => Student::where('status', null)->where('assain_user', Auth::id())->count(),
                'countLead' => Student::where('assain_user', Auth::id())->where('status', 1)->count(),
                'countProspect' => Student::where('assain_user', Auth::id())->where('status', 2)->count(),
                'countonBoard' => Student::where('assain_user', Auth::id())->where('status', 3)->count(),
                'countArchive' => Student::where('assain_user', Auth::id())->where('status', 4)->count(),
            ]);
        }
    }

    public function lead(Request $request, StudentLead $student)
    {
        try {
            $this->authorize('Student.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $perPage = $request->query('per_page', 10);

        $user = Auth::user();
        if ($user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {

            return Inertia::render('allpages/Agency/Student/studentlead', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::count(),
                'countPending' => Student::where('status', null)->count(),
                'countLead' => Student::where('status', 1)->count(),
                'countProspect' => Student::where('status', 2)->count(),
                'countonBoard' => Student::where('status', 3)->count(),
                'countArchive' => Student::where('status', 4)->count(),
            ]);
        } else {

            return Inertia::render('allpages/Agency/Student/studentlead', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::where('assain_user', Auth::id())->count(),
                'countPending' => Student::where('status', null)->where('assain_user', Auth::id())->count(),
                'countLead' => Student::where('assain_user', Auth::id())->where('status', 1)->count(),
                'countProspect' => Student::where('assain_user', Auth::id())->where('status', 2)->count(),
                'countonBoard' => Student::where('assain_user', Auth::id())->where('status', 3)->count(),
                'countArchive' => Student::where('assain_user', Auth::id())->where('status', 4)->count(),
            ]);
        }
    }

    public function pending(Request $request, StudentPending $student)
    {
        try {
            $this->authorize('Student.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $perPage = $request->query('per_page', 10);

        $user = Auth::user();
        if ($user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {

            return Inertia::render('allpages/Agency/Student/studentpending', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::count(),
                'countPending' => Student::where('status', null)->count(),
                'countLead' => Student::where('status', 1)->count(),
                'countProspect' => Student::where('status', 2)->count(),
                'countonBoard' => Student::where('status', 3)->count(),
                'countArchive' => Student::where('status', 4)->count(),
            ]);
        } else {

            return Inertia::render('allpages/Agency/Student/studentpending', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::where('assain_user', Auth::id())->count(),
                'countPending' => Student::where('status', null)->where('assain_user', Auth::id())->count(),
                'countLead' => Student::where('assain_user', Auth::id())->where('status', 1)->count(),
                'countProspect' => Student::where('assain_user', Auth::id())->where('status', 2)->count(),
                'countonBoard' => Student::where('assain_user', Auth::id())->where('status', 3)->count(),
                'countArchive' => Student::where('assain_user', Auth::id())->where('status', 4)->count(),
            ]);
        }
    }

    public function prospect(Request $request, StudentProspect $student)
    {
        try {
            $this->authorize('Student.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $perPage = $request->query('per_page', 10);

        $user = Auth::user();
        if ($user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {

            return Inertia::render('allpages/Agency/Student/studentprospect', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::count(),
                'countPending' => Student::where('status', null)->count(),
                'countLead' => Student::where('status', 1)->count(),
                'countProspect' => Student::where('status', 2)->count(),
                'countonBoard' => Student::where('status', 3)->count(),
                'countArchive' => Student::where('status', 4)->count(),
            ]);
        } else {

            return Inertia::render('allpages/Agency/Student/studentprospect', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::where('assain_user', Auth::id())->count(),
                'countPending' => Student::where('status', null)->where('assain_user', Auth::id())->count(),
                'countLead' => Student::where('assain_user', Auth::id())->where('status', 1)->count(),
                'countProspect' => Student::where('assain_user', Auth::id())->where('status', 2)->count(),
                'countonBoard' => Student::where('assain_user', Auth::id())->where('status', 3)->count(),
                'countArchive' => Student::where('assain_user', Auth::id())->where('status', 4)->count(),
            ]);
        }
    }

    public function onBoard(Request $request, StudentOnBoard $student)
    {
        try {
            $this->authorize('Student.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $perPage = $request->query('per_page', 10);

        $user = Auth::user();
        if ($user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {

            return Inertia::render('allpages/Agency/Student/studentonboard', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::count(),
                'countPending' => Student::where('status', null)->count(),
                'countLead' => Student::where('status', 1)->count(),
                'countProspect' => Student::where('status', 2)->count(),
                'countonBoard' => Student::where('status', 3)->count(),
                'countArchive' => Student::where('status', 4)->count(),
            ]);
        } else {

            return Inertia::render('allpages/Agency/Student/studentonboard', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::where('assain_user', Auth::id())->count(),
                'countPending' => Student::where('status', null)->where('assain_user', Auth::id())->count(),
                'countLead' => Student::where('assain_user', Auth::id())->where('status', 1)->count(),
                'countProspect' => Student::where('assain_user', Auth::id())->where('status', 2)->count(),
                'countonBoard' => Student::where('assain_user', Auth::id())->where('status', 3)->count(),
                'countArchive' => Student::where('assain_user', Auth::id())->where('status', 4)->count(),
            ]);
        }
    }

    public function archive(Request $request, StudentArchive $student)
    {
        try {
            $this->authorize('Student.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $perPage = $request->query('per_page', 10);

        $user = Auth::user();
        if ($user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {

            return Inertia::render('allpages/Agency/Student/studentarchive', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::count(),
                'countPending' => Student::where('status', null)->count(),
                'countLead' => Student::where('status', 1)->count(),
                'countProspect' => Student::where('status', 2)->count(),
                'countonBoard' => Student::where('status', 3)->count(),
                'countArchive' => Student::where('status', 4)->count(),
            ]);
        } else {

            return Inertia::render('allpages/Agency/Student/studentarchive', [
                'allsearch' => Student::with(['source'])->get(),
                'allcountry' => Country::get(),
                'assaignUser' => Student::with(['assainuser'])->get(),
                'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
                'filters' => $request->only(['name', 'status']),
                'countAll' => Student::where('assain_user', Auth::id())->count(),
                'countPending' => Student::where('status', null)->where('assain_user', Auth::id())->count(),
                'countLead' => Student::where('assain_user', Auth::id())->where('status', 1)->count(),
                'countProspect' => Student::where('assain_user', Auth::id())->where('status', 2)->count(),
                'countonBoard' => Student::where('assain_user', Auth::id())->where('status', 3)->count(),
                'countArchive' => Student::where('assain_user', Auth::id())->where('status', 4)->count(),
            ]);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $this->authorize('Student.create');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        try {
            $this->authorize('Student.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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


        $exists = Student::where('phone', $validated['phone'])
            ->where('dateofbirth', $validated['dateofbirth'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'phone' => 'This phone number with date of birth already exists.',
            ]);
        }

        Student::create([
            // Basic Info
            'student_id'      => $validated['student_id'] ?? null,
            'fname'           => $validated['fname'],
            'lname'           => $validated['lname'],
            'dateofbirth'     => $validated['dateofbirth'] ?? null,
            'gender'          => $validated['gender'] ?? null,
            'email'           => $validated['email'] ?? null,
            'phone'           => $validated['phone'] ?? null,
            'ename'           => $validated['ename'] ?? null,
            'ephone'           => $validated['ephone'] ?? null,
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



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {

        return response()->json([
            'success' => true,
            'student' => $student
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {

        $update = Student::where('id', $student->id)
            ->update(
                [
                    'fname' => $request->fname,
                    'lname' => $request->lname,
                    'dateofbirth' => $request->dateofbirth,
                    'gender' => $request->gender,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'ename' => $request->ename,
                    'ephone' => $request->ephone,
                    'contactpre' => $request->contactpre
                ]
            );
        if ($update) {
            StudentActivities::create([
                'student_id' => $student->id,
                'title' => 'has student updated',
                'fristactivity' => null,
                'lastactivity' => null,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('message', 'Stusent update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
