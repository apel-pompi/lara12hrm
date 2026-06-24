<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Student\Student;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Default\Country;
use App\Models\Student\InactiveLeadTransferLog;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentSource;
use App\Models\Student\StudentStage;
use App\Models\User;
use App\Models\Student\StudentUtility;
use App\Models\Default\UserWiseForm;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        $counts = $this->allCounts($baseStudentQuery);

        return Inertia::render('allpages/Agency/Student/student', array_merge($counts, [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters' => $request->only(['name', 'status']),
            'showInactiveTabs' => $this->showInactiveTabs(),
        ]));
    }

    /**
     * Get transfer users based on user_wise_forms.counsilor_id.
     * Admin-like roles see all users.
     */
    private function getTransferUsers($authUser)
    {
        /** @var \Spatie\Permission\Traits\HasRoles $authUser */
        if ($authUser->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {
            return User::select('id', 'name')->orderBy('name')->get();
        }

        $counsilorJsons = UserWiseForm::whereNotNull('counsilor_id')->pluck('counsilor_id');

        $userIds = collect();
        foreach ($counsilorJsons as $json) {
            $arr = json_decode($json, true);
            if (is_array($arr)) {
                $userIds = $userIds->merge($arr);
            }
        }

        $userIds = $userIds->filter()->unique()->values()->all();

        if (empty($userIds)) {
            return collect();
        }

        return User::select('id', 'name')->whereIn('id', $userIds)->orderBy('name')->get();
    }

    private function showInactiveTabs(): bool
    {
        $user = Auth::user();
        return $user->hasAnyRole(['superadmin', 'Admin', 'Manager']);
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
                    $students->whereRaw("DATE(created_at) LIKE ?", ["%{$query}%"]);
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

        $counts = $this->allCounts($baseStudentQuery);

        return Inertia::render('allpages/Agency/Student/studentlead', array_merge($counts, [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters' => $request->only(['id', 'student_id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at']),
            'showInactiveTabs' => $this->showInactiveTabs(),
        ]));
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
                    $students->whereRaw("DATE(created_at) LIKE ?", ["%{$query}%"]);
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

        $counts = $this->allCounts($baseStudentQuery);

        $users = $this->getTransferUsers($user);

        return Inertia::render('allpages/Agency/Student/studentpending', array_merge($counts, [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'users' => $users,
            'filters' => $request->only(['id', 'student_id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at']),
            'showInactiveTabs' => $this->showInactiveTabs(),
        ]));
    }

    public function SearchPending(Request $request)
    {
        $type = $request->get('type', 'name'); // student_id | name | phone | assain | date | status
        $query = $request->get('q', '');
        // Base query
        $students = Student::query()
            ->select('id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at')
            ->whereNull('status')
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

        $counts = $this->allCounts($baseStudentQuery);

        return Inertia::render('allpages/Agency/Student/studentprospect', array_merge($counts, [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters' => $request->only(['id', 'student_id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at']),
            'showInactiveTabs' => $this->showInactiveTabs(),
        ]));
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
                    $students->whereRaw("DATE(created_at) LIKE ?", ["%{$query}%"]);
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

        $counts = $this->allCounts($baseStudentQuery);

        return Inertia::render('allpages/Agency/Student/studentonboard', array_merge($counts, [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters' => $request->only(['id', 'student_id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at']),
            'showInactiveTabs' => $this->showInactiveTabs(),
        ]));
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
                    $students->whereRaw("DATE(created_at) LIKE ?", ["%{$query}%"]);
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

        $counts = $this->allCounts($baseStudentQuery);

        return Inertia::render('allpages/Agency/Student/studentarchive', array_merge($counts, [
            'student' => $student->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters' => $request->only(['id', 'student_id', 'fname', 'lname', 'phone', 'assain_user', 'status', 'created_at']),
            'showInactiveTabs' => $this->showInactiveTabs(),
        ]));
    }

    public function SearchArchive(Request $request)
    {
        $type  = $request->get('type', 'name');
        $query = $request->get('q', '');

        $students = Student::query()
            ->select('id', 'fname', 'lname', 'phone', 'assain_user')
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
                    $students->whereRaw("DATE(created_at) LIKE ?", ["%{$query}%"]);
                    break;
            }
        }

        return response()->json(
            $students->orderBy('id', 'desc')->limit(500)->get()
        );
    }

    public function SearchInactive(Request $request)
    {
        $type  = $request->get('type', 'name');
        $query = $request->get('q', '');
        $period = $request->get('period', '1month');

        $months = match ($period) {
            '1month' => 1,
            '3month' => 3,
            '6month' => 6,
            default  => 1,
        };

        $cutoffDate = Carbon::now()->subMonths($months);
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $user = Auth::user();

        $baseStudentQuery = Student::query();
        if (! $user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {
            $baseStudentQuery->where('assain_user', $user->id);
        }

        $maxActivityDates = DB::table('student_activities')
            ->whereNull('deleted_at')
            ->groupBy('student_id')
            ->select('student_id', DB::raw('MAX(created_at) as max_act_date'))
            ->pluck('max_act_date', 'student_id')
            ->toArray();

        $inactiveIds = (clone $baseStudentQuery)
            ->where('status', 1)
            ->pluck('id')
            ->filter(function ($id) use ($maxActivityDates, $cutoffDate) {
                $lastAct = $maxActivityDates[$id] ?? '1900-01-01';
                return Carbon::parse($lastAct)->lt($cutoffDate);
            })
            ->values()
            ->all();

        $students = Student::query()
            ->select('id', 'fname', 'lname', 'phone', 'assain_user', 'created_at')
            ->selectRaw('DATE(created_at) as date')
            ->whereIn('id', $inactiveIds)
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
                    $students->whereRaw("DATE(created_at) LIKE ?", ["%{$query}%"]);
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
            ->with('success', 'Student created successfully.');
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

        return back()->with('message', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }

    // ─────────────────────────────────────────────
    // PRIVATE HELPER: all status + inactive counts
    // ─────────────────────────────────────────────
    private function allCounts($baseStudentQuery): array
    {
        $cutoff1 = Carbon::now()->subMonth();
        $cutoff3 = Carbon::now()->subMonths(3);
        $cutoff6 = Carbon::now()->subMonths(6);

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

        // Optimized inactive leads counts in memory
        $maxActivityDates = DB::table('student_activities')
            ->whereNull('deleted_at')
            ->groupBy('student_id')
            ->select('student_id', DB::raw('MAX(created_at) as max_act_date'))
            ->pluck('max_act_date', 'student_id')
            ->toArray();

        $leadStudents = (clone $baseStudentQuery)
            ->where('status', 1)
            ->select('id')
            ->get();

        $c1 = 0;
        $c3 = 0;
        $c6 = 0;

        foreach ($leadStudents as $s) {
            $lastAct = $maxActivityDates[$s->id] ?? '1900-01-01';
            $lastActTime = Carbon::parse($lastAct);
            if ($lastActTime->lt($cutoff1)) $c1++;
            if ($lastActTime->lt($cutoff3)) $c3++;
            if ($lastActTime->lt($cutoff6)) $c6++;
        }

        return [
            'countAll'            => $statusCounts->countAll ?? 0,
            'countPending'        => $statusCounts->countPending ?? 0,
            'countLead'           => $statusCounts->countLead ?? 0,
            'countProspect'       => $statusCounts->countProspect ?? 0,
            'countonBoard'        => $statusCounts->countonBoard ?? 0,
            'countArchive'        => $statusCounts->countArchive ?? 0,
            'countInactive1Month' => $c1,
            'countInactive3Month' => $c3,
            'countInactive6Month' => $c6,
        ];
    }

    // ─────────────────────────────────────────────
    // INACTIVE LEAD PAGES
    // ─────────────────────────────────────────────
    private function inactiveLeadsPage(Request $request, string $period, string $view)
    {
        try {
            $this->authorize('Student.index');
        } catch (AuthorizationException $e) {
            return back()->with(['error' => true, 'message' => 'You are not authorized.']);
        }

        $months     = match ($period) {
            '1month' => 1,
            '3month' => 3,
            '6month' => 6,
            default  => 1,
        };
        $cutoffDate = Carbon::now()->subMonths($months);
        $perPage    = $request->query('per_page', 10);
        $user       = Auth::user();

        $baseStudentQuery = Student::query();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        if (!$user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {
            $baseStudentQuery->where('assain_user', $user->id);
        }

        $counts = $this->allCounts($baseStudentQuery);

        // Optimized fetching of inactive students
        $maxActivityDates = DB::table('student_activities')
            ->whereNull('deleted_at')
            ->groupBy('student_id')
            ->select('student_id', DB::raw('MAX(created_at) as max_act_date'))
            ->pluck('max_act_date', 'student_id')
            ->toArray();

        $allLeadStudents = (clone $baseStudentQuery)
            ->where('status', 1)
            ->select('id')
            ->get();

        $inactiveIds = [];
        foreach ($allLeadStudents as $s) {
            $lastAct = $maxActivityDates[$s->id] ?? '1900-01-01';
            if (Carbon::parse($lastAct)->lt($cutoffDate)) {
                $inactiveIds[] = $s->id;
            }
        }

        $studentsQuery = Student::query()
            ->with(['user:id,name', 'assainuser:id,name', 'source:id,name', 'stage:id,name'])
            ->whereIn('id', $inactiveIds)
            ->orderBy('id', 'DESC');

        if ($request->get('name')) {
            $name = $request->get('name');
            $studentsQuery->where(function ($sql) use ($name) {
                $sql->where('fname', 'like', "%{$name}%")
                    ->orWhere('lname', 'like', "%{$name}%");
            });
        }

        if ($request->get('phone')) {
            $phone = preg_replace('/\D/', '', $request->get('phone'));
            $studentsQuery->whereRaw(
                "RIGHT(REGEXP_REPLACE(phone,'[^0-9]',''),11) LIKE ?",
                ["%{$phone}%"]
            );
        }

        if ($request->get('user')) {
            $studentsQuery->where('assain_user', $request->get('user'));
        }

        if ($request->get('created_at')) {
            $studentsQuery->whereRaw("DATE(created_at) LIKE ?", ["%{$request->get('created_at')}%"]);
        }

        $students = $studentsQuery->paginate($perPage)->withQueryString();

        $students->getCollection()->transform(function ($student) use ($maxActivityDates) {
            $student->last_activity_at = $maxActivityDates[$student->id] ?? '1900-01-01';
            return $student;
        });

        $users = $this->getTransferUsers($user);

        return Inertia::render($view, array_merge($counts, [
            'student'  => $students,
            'period'   => $period,
            'users'    => $users,
            'filters'  => $request->only(['name', 'phone', 'user', 'created_at']),
            'showInactiveTabs' => $this->showInactiveTabs(),
        ]));
    }

    public function inactiveLeads1Month(Request $request)
    {
        return $this->inactiveLeadsPage($request, '1month', 'allpages/Agency/Student/studentinactive');
    }

    public function inactiveLeads3Month(Request $request)
    {
        return $this->inactiveLeadsPage($request, '3month', 'allpages/Agency/Student/studentinactive');
    }

    public function inactiveLeads6Month(Request $request)
    {
        return $this->inactiveLeadsPage($request, '6month', 'allpages/Agency/Student/studentinactive');
    }

    // ─────────────────────────────────────────────
    // TRANSFER INACTIVE LEADS
    // ─────────────────────────────────────────────
    public function transferInactiveLeads(Request $request)
    {
        
        try {
            $this->authorize('Student.index');
        } catch (AuthorizationException $e) {
            return back()->with(['error' => true, 'message' => 'You are not authorized.']);
        }

        $request->validate([
            'to_user_id'   => 'required|exists:users,id',
            'student_ids'  => 'nullable|array',
            'student_ids.*' => 'integer|exists:students,id',
            'transfer_all' => 'nullable|boolean',
            'period'       => 'nullable|in:1month,3month,6month',
        ]);

        $toUserId    = $request->to_user_id;
        $transferAll = $request->boolean('transfer_all');
        $period      = $request->period;
        $studentIds  = $request->student_ids ?? [];
        $note        = $request->note ?? '';
        

        $user        = Auth::user();
        // Ensure non-admin users can only transfer to users allowed by user_wise_forms
        $allowedIds = $this->getTransferUsers($user)->pluck('id')->toArray();
        if (! $user->hasAnyRole(['superadmin', 'Admin', 'Manager']) && ! in_array($toUserId, $allowedIds)) {
            return back()->with('error', 'You are not allowed to transfer to the selected user.');
        }
        $transferType = 'selected';
        $transferredStudentIds = [];
        $count = 0;
        $fromUserId = null;
        if($note=='Pending leads'){
            $note = 'Pending leads';
        }else{
            $note = 'Inactive leads';
        }

        DB::transaction(function () use (
            $transferAll,
            $period,
            $studentIds,
            $toUserId,
            $user,
            &$fromUserId,
            &$transferType,
            &$transferredStudentIds,
            &$count,
            &$note
        ) {
            if ($transferAll && $period) {
                $months     = match ($period) {
                    '1month' => 1,
                    '3month' => 3,
                    '6month' => 6,
                    default  => 1,
                };
                $cutoffDate = Carbon::now()->subMonths($months);

                $query = Student::where('status', 1)
                    ->whereRaw(
                        'COALESCE((SELECT MAX(sa.created_at) FROM student_activities sa WHERE sa.student_id = students.id AND sa.deleted_at IS NULL), "1900-01-01") < ?',
                        [$cutoffDate]
                    );

                /** @var \Spatie\Permission\Traits\HasRoles $user */
                if (!$user->hasAnyRole(['superadmin', 'Admin', 'Manager'])) {
                    $query->where('assain_user', $user->id);
                }

                $studentRows = $query->select('id', 'assain_user')->get();
                $transferredStudentIds = $studentRows->pluck('id')->toArray();
                $fromUserIds = $studentRows->pluck('assain_user')->filter()->unique()->values();
                $count = count($transferredStudentIds);

                if ($count > 0) {
                    $query->update(['assain_user' => $toUserId]);
                }

                if ($fromUserIds->count() === 1) {
                    $fromUserId = $fromUserIds->first();
                }

                // Resolve user names for clearer notes
                $toUserName = User::find($toUserId)?->name ?? "User ID {$toUserId}";
                if ($fromUserIds->isEmpty()) {
                    $fromUserName = 'Unknown';
                } elseif ($fromUserIds->count() === 1) {
                    $fromUserName = User::find($fromUserId)?->name ?? "User ID {$fromUserId}";
                } else {
                    $fromUserName = User::whereIn('id', $fromUserIds->toArray())->pluck('name')->unique()->implode(', ');
                }

                $transferType = 'bulk';
                $note = $fromUserIds->count() > 1
                    ? "Transferred {$note} from {$fromUserName} to {$toUserName}."
                    : "Transferred {$note} from {$fromUserName} to {$toUserName}.";
            } elseif (!empty($studentIds)) {
                $studentRows = Student::whereIn('id', $studentIds)->select('id', 'assain_user')->get();
                $transferredStudentIds = $studentRows->pluck('id')->toArray();
                $fromUserIds = $studentRows->pluck('assain_user')->filter()->unique()->values();
                $count = count($transferredStudentIds);

                if ($count > 0) {
                    Student::whereIn('id', $transferredStudentIds)->update(['assain_user' => $toUserId]);
                }

                if ($fromUserIds->count() === 1) {
                    $fromUserId = $fromUserIds->first();
                }

                // Resolve user names for clearer notes
                $toUserName = User::find($toUserId)?->name ?? "User ID {$toUserId}";
                if ($fromUserIds->isEmpty()) {
                    $fromUserName = 'Unknown';
                } elseif ($fromUserIds->count() === 1) {
                    $fromUserName = User::find($fromUserId)?->name ?? "User ID {$fromUserId}";
                } else {
                    $fromUserName = User::whereIn('id', $fromUserIds->toArray())->pluck('name')->unique()->implode(', ');
                }

                $note = $fromUserIds->count() > 1
                    ? "Transferred selected {$note} from {$fromUserName} to {$toUserName}."
                    : "Transferred selected {$note} from {$fromUserName} to {$toUserName}.";
            }

            if ($count > 0) {
                InactiveLeadTransferLog::create([
                    'student_ids' => $transferredStudentIds,
                    'student_count' => $count,
                    'from_user_id' => $fromUserId,
                    'to_user_id' => $toUserId,
                    'transferred_by_user_id' => $user->id,
                    'period' => $transferAll ? $period : null,
                    'transfer_type' => $transferType,
                    'note' => $note,
                ]);
            }
        });

        if ($count > 0) {
            return back()->with('success', "{$count} leads were transferred successfully.");
        }

        return back()->with('error', 'No lead was selected.');
    }

    public function transferInactiveLeadLogs(Request $request)
    {
        try {
            $this->authorize('Student.index');
        } catch (AuthorizationException $e) {
            return back()->with(['error' => true, 'message' => 'You are not authorized.']);
        }

        $logs = InactiveLeadTransferLog::with([
                'fromUser:id,name',
                'toUser:id,name',
                'transferredBy:id,name',
            ])
            ->orderByDesc('created_at');

        if ($request->filled('from_user_id')) {
            $logs->where('from_user_id', $request->from_user_id);
        }

        if ($request->filled('to_user_id')) {
            $logs->where('to_user_id', $request->to_user_id);
        }

        if ($request->filled('period')) {
            $logs->where('period', $request->period);
        }

        if ($request->filled('transfer_type')) {
            $logs->where('transfer_type', $request->transfer_type);
        }

        return response()->json($logs->paginate(50));
    }
}
