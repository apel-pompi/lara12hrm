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

        $baseStudentQuery = Student::query();

        /** @var \Spatie\Permission\Traits\HasRoles $user */
        if (! $user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {
            $baseStudentQuery->where('assain_user', $user->id);
        }

        $statusCounts = (clone $baseStudentQuery)
            ->selectRaw("
            COUNT(*) as countAll,
            SUM(status IS NULL) as countPending,
            SUM(status = 1) as countLead,
            SUM(status = 2) as countProspect,
            SUM(status = 3) as countonBoard,
            SUM(status = 4) as countArchive
        ")
            ->first();

        return Inertia::render('allpages/Agency/Student/student', [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters' => $request->only(['name', 'status']),

            'countAll' => $statusCounts->countAll,
            'countPending' => $statusCounts->countPending,
            'countLead' => $statusCounts->countLead,
            'countProspect' => $statusCounts->countProspect,
            'countonBoard' => $statusCounts->countonBoard,
            'countArchive' => $statusCounts->countArchive,
        ]);
    }

    public function Search(Request $request)
    {
        $type = $request->get('type', 'name'); //  name | phone | assain | date | status
        $query = $request->get('q', '');
        // Base query
        $students = Student::query()
            ->select('id', 'fname', 'lname', 'phone', 'assain_user')
            ->selectRaw('DATE(created_at) as date')
            ->where('status', 3)
            ->with(['assainuser:id,name']);

        
        if ($query) {
            $q = preg_replace('/\D/', '', $query); // remove non-digit for phone search

            switch ($type) {

                case 'name':
                    $students->where(function ($sql) use ($query) {
                        $sql->where('fname', 'like', "%{$query}%")
                            ->orWhere('lname', 'like', "%{$query}%");
                    });
                    break;

                case 'phone':
                    $students->whereRaw(
                        "RIGHT(REGEXP_REPLACE(phone,'[^0-9]',''),11) LIKE ?",
                        ["%{$q}%"]
                    );
                    break;

                case 'assain':
                    $students->whereHas('assainuser', function ($sql) use ($query) {
                        $sql->where('name', 'like', "%{$query}%");
                    });
                    break;

                case 'date':
                    // search by created_at date YYYY-MM-DD
                    $students->whereRaw("DATE(created_at) LIKE ?",["%{$query}%"]);
                    break;

                case 'status':
                    $students->where(function ($sql) use ($query) {

                        $statusMap = [
                            'pending'  => null,
                            'lead'     => 1,
                            'prospect' => 2,
                            'onboard'  => 3,
                            'archive'  => 4,
                        ];

                        $key = strtolower($query);

                        if (array_key_exists($key, $statusMap)) {
                            if ($statusMap[$key] === null) {
                                $sql->whereNull('status');
                            } else {
                                $sql->where('status', $statusMap[$key]);
                            }
                        }
                    });
                    break;
            }
        }
        $students = $students->orderBy('id', 'desc')->limit(500)->get();
        return response()->json($students);
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

        $baseStudentQuery = Student::query();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        if (! $user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {
            $baseStudentQuery->where('assain_user', $user->id);
        }

        $statusCounts = (clone $baseStudentQuery)
            ->selectRaw("
            COUNT(*) as countAll,
            SUM(status IS NULL) as countPending,
            SUM(status = 1) as countLead,
            SUM(status = 2) as countProspect,
            SUM(status = 3) as countonBoard,
            SUM(status = 4) as countArchive
        ")
            ->first();


        return Inertia::render('allpages/Agency/Student/studentlead', [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters' => $request->only(['id', 'student_id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at']),
            // counts
            'countAll' => $statusCounts->countAll,
            'countPending' => $statusCounts->countPending,
            'countLead' => $statusCounts->countLead,
            'countProspect' => $statusCounts->countProspect,
            'countonBoard' => $statusCounts->countonBoard,
            'countArchive' => $statusCounts->countArchive,
        ]);
    }

    public function SearchLead(Request $request)
    {
        $type  = $request->get('type', 'name');
        $query = $request->get('q', '');

        $students = Student::query()
            ->select('id', 'fname', 'lname', 'phone', 'assain_user')
            ->selectRaw('DATE(created_at) as date')
            ->where('status', 1)
            ->with(['assainuser:id,name']);

        if ($query !== '') {

            $q = preg_replace('/\D/', '', $query);

            switch ($type) {

                case 'name':
                    $students->where(function ($sql) use ($query) {
                        $sql->where('fname', 'like', "%{$query}%")
                            ->orWhere('lname', 'like', "%{$query}%");
                    });
                    break;

                case 'phone':
                    $students->whereRaw(
                        "RIGHT(REGEXP_REPLACE(phone,'[^0-9]',''),11) LIKE ?",
                        ["%{$q}%"]
                    );
                    break;

                case 'assain':
                    $students->whereHas('assainuser', function ($sql) use ($query) {
                        $sql->where('name', 'like', "%{$query}%");
                    });
                    break;
                case 'date':
                    // search by created_at date YYYY-MM-DD
                    $students->whereRaw("DATE(created_at) LIKE ?",["%{$query}%"]);
                    break;
            }
        }

        return response()->json(
            $students->orderBy('id', 'desc')->limit(500)->get()
        );
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

        $baseStudentQuery = Student::query();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        if (! $user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {
            $baseStudentQuery->where('assain_user', $user->id);
        }

        $statusCounts = (clone $baseStudentQuery)
            ->selectRaw("
            COUNT(*) as countAll,
            SUM(status IS NULL) as countPending,
            SUM(status = 1) as countLead,
            SUM(status = 2) as countProspect,
            SUM(status = 3) as countonBoard,
            SUM(status = 4) as countArchive
        ")
            ->first();


        return Inertia::render('allpages/Agency/Student/studentpending', [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters' => $request->only(['id', 'student_id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at']),
            // counts
            'countAll' => $statusCounts->countAll,
            'countPending' => $statusCounts->countPending,
            'countLead' => $statusCounts->countLead,
            'countProspect' => $statusCounts->countProspect,
            'countonBoard' => $statusCounts->countonBoard,
            'countArchive' => $statusCounts->countArchive,
        ]);
    }

    public function SearchPending(Request $request)
    {
        $type = $request->get('type', 'name'); // student_id | name | phone | assain | date | status
        $query = $request->get('q', '');
        // Base query
        $students = Student::query()
            ->select('id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at')
            ->where('status', 0)
            ->with(['assainuser:id,name']);

        if ($query) {
            $q = preg_replace('/\D/', '', $query); // remove non-digit for phone search

            switch ($type) {

                case 'name':
                    $students->where(function ($sql) use ($query) {
                        $sql->where('fname', 'like', "%{$query}%")
                            ->orWhere('lname', 'like', "%{$query}%");
                    });
                    break;

                case 'phone':
                    $students->whereRaw(
                        "RIGHT(REGEXP_REPLACE(phone,'[^0-9]',''),11) LIKE ?",
                        ["%{$q}%"]
                    );
                    break;

                case 'assain':
                    $students->whereHas('assainuser', function ($sql) use ($query) {
                        $sql->where('name', 'like', "%{$query}%");
                    });
                    break;

                case 'date':
                    // search by created_at date YYYY-MM-DD
                    $students->whereDate('created_at', 'like', "%{$query}%");
                    break;

                
            }
        }
        $students = $students->orderBy('id', 'desc')->limit(500)->get();
        return response()->json($students);
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

        $baseStudentQuery = Student::query();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        if (! $user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {
            $baseStudentQuery->where('assain_user', $user->id);
        }

        $statusCounts = (clone $baseStudentQuery)
            ->selectRaw("
            COUNT(*) as countAll,
            SUM(status IS NULL) as countPending,
            SUM(status = 1) as countLead,
            SUM(status = 2) as countProspect,
            SUM(status = 3) as countonBoard,
            SUM(status = 4) as countArchive
        ")
            ->first();

        return Inertia::render('allpages/Agency/Student/studentprospect', [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters' => $request->only(['id', 'student_id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at']),

            // counts
            'countAll' => $statusCounts->countAll,
            'countPending' => $statusCounts->countPending,
            'countLead' => $statusCounts->countLead,
            'countProspect' => $statusCounts->countProspect,
            'countonBoard' => $statusCounts->countonBoard,
            'countArchive' => $statusCounts->countArchive,
        ]);
    }

    public function SearchProspect(Request $request)
    {
        $type  = $request->get('type', 'name');
        $query = $request->get('q', '');

        $students = Student::query()
            ->select('id', 'student_id', 'fname', 'lname', 'phone', 'assain_user')
            ->selectRaw('DATE(created_at) as date')
            ->where('status', 2)
            ->with(['assainuser:id,name']);

        if ($query !== '') {

            $q = preg_replace('/\D/', '', $query);

            switch ($type) {

                case 'student_id':
                    $students
                        ->whereNotNull('student_id')
                        ->where('student_id', 'like', "%{$query}%");
                    break;

                case 'name':
                    $students->where(function ($sql) use ($query) {
                        $sql->where('fname', 'like', "%{$query}%")
                            ->orWhere('lname', 'like', "%{$query}%");
                    });
                    break;

                case 'phone':
                    $students->whereRaw(
                        "RIGHT(REGEXP_REPLACE(phone,'[^0-9]',''),11) LIKE ?",
                        ["%{$q}%"]
                    );
                    break;

                case 'assain':
                    $students->whereHas('assainuser', function ($sql) use ($query) {
                        $sql->where('name', 'like', "%{$query}%");
                    });
                    break;
                case 'date':
                    // search by created_at date YYYY-MM-DD
                    $students->whereRaw("DATE(created_at) LIKE ?",["%{$query}%"]);
                    break;
            }
        }

        return response()->json(
            $students->orderBy('id', 'desc')->limit(500)->get()
        );
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

        $baseStudentQuery = Student::query();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        if (! $user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {
            $baseStudentQuery->where('assain_user', $user->id);
        }

        $statusCounts = (clone $baseStudentQuery)
            ->selectRaw("
            COUNT(*) as countAll,
            SUM(status IS NULL) as countPending,
            SUM(status = 1) as countLead,
            SUM(status = 2) as countProspect,
            SUM(status = 3) as countonBoard,
            SUM(status = 4) as countArchive
        ")
            ->first();
        return Inertia::render('allpages/Agency/Student/studentonboard', [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters' => $request->only(['id', 'student_id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at']),
            // counts
            'countAll' => $statusCounts->countAll,
            'countPending' => $statusCounts->countPending,
            'countLead' => $statusCounts->countLead,
            'countProspect' => $statusCounts->countProspect,
            'countonBoard' => $statusCounts->countonBoard,
            'countArchive' => $statusCounts->countArchive,
        ]);
    }

    public function SearchOnBoard(Request $request)
    {
        $type  = $request->get('type', 'name');
        $query = $request->get('q', '');

        $students = Student::query()
            ->select('id', 'student_id', 'fname', 'lname', 'phone', 'assain_user')
            ->selectRaw('DATE(created_at) as date')
            ->where('status', 3)
            ->with(['assainuser:id,name']);

        if ($query !== '') {

            $q = preg_replace('/\D/', '', $query);

            switch ($type) {

                case 'student_id':
                    $students
                        ->whereNotNull('student_id')
                        ->where('student_id', 'like', "%{$query}%");
                    break;

                case 'name':
                    $students->where(function ($sql) use ($query) {
                        $sql->where('fname', 'like', "%{$query}%")
                            ->orWhere('lname', 'like', "%{$query}%");
                    });
                    break;

                case 'phone':
                    $students->whereRaw(
                        "RIGHT(REGEXP_REPLACE(phone,'[^0-9]',''),11) LIKE ?",
                        ["%{$q}%"]
                    );
                    break;

                case 'assain':
                    $students->whereHas('assainuser', function ($sql) use ($query) {
                        $sql->where('name', 'like', "%{$query}%");
                    });
                    break;
                case 'date':
                    // search by created_at date YYYY-MM-DD
                    $students->whereRaw("DATE(created_at) LIKE ?",["%{$query}%"]);
                    break;
            }
        }

        return response()->json(
            $students->orderBy('id', 'desc')->limit(500)->get()
        );
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

        $baseStudentQuery = Student::query();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        if (! $user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {
            $baseStudentQuery->where('assain_user', $user->id);
        }

        $statusCounts = (clone $baseStudentQuery)
            ->selectRaw("
            COUNT(*) as countAll,
            SUM(status IS NULL) as countPending,
            SUM(status = 1) as countLead,
            SUM(status = 2) as countProspect,
            SUM(status = 3) as countonBoard,
            SUM(status = 4) as countArchive
        ")
            ->first();

        return Inertia::render('allpages/Agency/Student/studentarchive', [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters' => $request->only(['id', 'student_id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at']),


            // counts
            'countAll' => $statusCounts->countAll,
            'countPending' => $statusCounts->countPending,
            'countLead' => $statusCounts->countLead,
            'countProspect' => $statusCounts->countProspect,
            'countonBoard' => $statusCounts->countonBoard,
            'countArchive' => $statusCounts->countArchive,
        ]);
    }

    public function SearchArchive(Request $request)
    {
        $type  = $request->get('type', 'name');
        $query = $request->get('q', '');

        $students = Student::query()
            ->select('id','fname', 'lname', 'phone', 'assain_user')
            ->selectRaw('DATE(created_at) as date')
            ->where('status', 4)
            ->with(['assainuser:id,name']);

        if ($query !== '') {

            $q = preg_replace('/\D/', '', $query);

            switch ($type) {

                case 'name':
                    $students->where(function ($sql) use ($query) {
                        $sql->where('fname', 'like', "%{$query}%")
                            ->orWhere('lname', 'like', "%{$query}%");
                    });
                    break;

                case 'phone':
                    $students->whereRaw(
                        "RIGHT(REGEXP_REPLACE(phone,'[^0-9]',''),11) LIKE ?",
                        ["%{$q}%"]
                    );
                    break;

                case 'assain':
                    $students->whereHas('assainuser', function ($sql) use ($query) {
                        $sql->where('name', 'like', "%{$query}%");
                    });
                    break;
                case 'date':
                    // search by created_at date YYYY-MM-DD
                    $students->whereRaw("DATE(created_at) LIKE ?",["%{$query}%"]);
                    break;
            }
        }

        return response()->json(
            $students->orderBy('id', 'desc')->limit(500)->get()
        );
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
    protected function normalizePhone($phone)
    {
        $digits = preg_replace('/\D/', '', $phone);

        return substr($digits, -11);
    }

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

        $cleanPhone = $this->normalizePhone($validated['phone']);

        $existsPhone = Student::whereRaw("RIGHT(REGEXP_REPLACE(phone, '[^0-9]', ''), 11) = ?", [$cleanPhone])
            ->where('dateofbirth', $validated['dateofbirth'])
            ->exists();

        if ($existsPhone) {
            return back()->withErrors([
                'phone' => 'This phone number already exists.',
            ]);
        }

        $existsBirth = Student::where('dateofbirth', $validated['dateofbirth'])->exists();
        if ($existsBirth) {
            return back()->withErrors([
                'dateofbirth' => 'This date of birth already exists.',
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
