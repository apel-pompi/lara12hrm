<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\PersonalInfo;
use App\Http\Requests\PersonalInfo\StorePersonalInfoRequest;
use App\Models\HRM\Branch;
use App\Models\Default\gDrive;
use App\Models\HRM\Department;
use App\Models\HRM\Designation;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\PersonalInfoService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class PersonalInfoController extends Controller
{
    
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PersonalInfoService $personalInfoService)
    {
        try {
            $this->authorize('personal.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return Inertia::render('allpages/hrm/personalinfo', [
            'personalinfo' => $personalInfoService->get($request->query()),
            'filters'   => $personalInfoService->get($request->query()),
            'branch' => Branch::where('active', 1)->get(),
            'department' => Department::where('active', 1)->get(),
            'designation' => Designation::where('active', 1)->get(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonalInfoRequest $request)
    {
        try {
            $this->authorize('personal.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validated();

        $validated['active'] = $request->input('active', 0);

        if ($request->hasFile('photo')) {
            $filePath = public_path('storage/employee');
            // create folder if not exists
            if (!File::exists($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }
            $file = $request->file('photo');
            $file_name = time() . '_' .$file->getClientOriginalName();
            $file->move($filePath, $file_name);
            $validated['photo'] = $file_name;
        }

        PersonalInfo::create($validated);

        return redirect()
            ->route('personalinfo.index')
            ->with('success', 'Personal Information created successfully.');
    }

    public function updateStatus(Request $request, $PersonalInfo)
    {
        try {
            $this->authorize('personal.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $personalinfo = PersonalInfo::findOrFail($PersonalInfo);
        $updated = $personalinfo->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('personalinfo.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonalInfo $PersonalInfo)
    {
        try {
            $this->authorize('personal.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        if (!$PersonalInfo) {
            return response()->json(['message' => 'Personal Info not found'], 404);
        }
        $PersonalInfo->load([
            'branch' => function ($query) {
                $query->where('active', 1);
            },
            'department' => function ($query) {
                $query->where('active', 1);
            },
            'designation' => function ($query) {
                $query->where('active', 1);
            },
        ]);
        return response()->json($PersonalInfo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonalInfo $PersonalInfo)
    {
        try {
            $this->authorize('personal.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return response()->json([
            'success' => true,
            'data' => $PersonalInfo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $this->authorize('personal.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'empid'         => 'required',
            'empname'       => 'required',
            'joindate'      => 'required',
            'branch_id'     => 'required',
            'dept_id'       => 'required',
            'des_id'        => 'required',
            'dateofbirth'   => 'required',
            'gender'        => 'required',
            'present'       => 'required',
            'permanent'     => 'required',
            'phonepersonal' => 'required',
            'phoneoffice'   => 'required',
            'email'         => 'required|email',
            'blood'         => 'required',
            'nidpass'       => 'required',
            'photo'         => '',
            'active'        => '',
        ]);

        $personalInfo = PersonalInfo::findOrFail($id);

        if ($request->hasFile('photo')) {
            $filePath = public_path('storage/employee');
            // create folder if not exists
            if (!File::exists($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($filePath, $fileName);
            // delete old photo if exists
            if (!empty($personalInfo->photo)) {
                $oldImage = public_path('storage/employee/' . $personalInfo->photo);
                if (File::exists($oldImage)) {
                    File::delete($oldImage);
                }
            }
            $validated['photo'] = $fileName;
        }

        $personalInfo->update($validated);

        return redirect()
            ->route('personalinfo.index')
            ->with('success', 'Personal Information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonalInfo $PersonalInfo)
    {
        try {
            $this->authorize('personal.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        try {
            if (!empty($PersonalInfo->photo)) {
                $oldImage = public_path('storage/employee/' . $PersonalInfo->photo);
                if (File::exists($oldImage)) {
                    File::delete($oldImage);
                }
            }
            $PersonalInfo->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete designation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
