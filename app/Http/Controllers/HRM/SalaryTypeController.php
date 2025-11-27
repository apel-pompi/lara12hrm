<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;

use App\Models\HRM\SalaryType;
use App\Http\Requests\SalaryType\StoreSalaryTypeRequest;
use App\Http\Requests\SalaryType\UpdateSalaryTypeRequest;
use App\Models\HRM\Branch;
use App\Services\SalaryTypeService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalaryTypeController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, SalaryTypeService $salaryTypeService)
    {
        try {
            $this->authorize('salaryType.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/hrm/salarytype', [

            'filters'   => $salaryTypeService->get($request->query()),
            'salaryType'   => $salaryTypeService->get($request->query()),
            'branch' => Branch::where('active', 1)->get(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalaryTypeRequest $request)
    {
        try {
            $this->authorize('salaryType.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['active'] = 0;
        $data['user_id'] = Auth::id();
        $store = SalaryType::create($data);
        if ($store) {
            return back()->with([
                'success' => true,
                'message' => 'Salary type setup created successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Salary type setup not created'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryType $salaryType)
    {
        try {
            $this->authorize('salaryType.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $salaryType->load('branch');
        if (!$salaryType) {
            return response()->json(['message' => 'Salary type setup not found'], 404);
        }
        return response()->json($salaryType);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryType $salaryType)
    {
        try {
            $this->authorize('salaryType.edit');

            return response()->json([
                'success' => true,
                'data' => $salaryType,
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalaryTypeRequest $request, SalaryType $salaryType)
    {
        try {
            $this->authorize('salaryType.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $salaryType->update($request->validated());

        return back()->with([
            'message' => 'Salary type setup update successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryType $salaryType)
    {
        try {
            $this->authorize('salaryType.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $salaryType->delete();
            return back()->with([
                'message' => 'Salary type setup delete successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Salary type setup setting.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $salaryType)
    {
        try {
            $this->authorize('salaryType.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $salaryType = SalaryType::findOrFail($salaryType);
        $updated = $salaryType->update(['active' =>  $validated['active']]);
        if ($updated) {
            return back()->with([
                'message' => 'Salary type status updated successfully.'
            ]);
        }
        return back()->with([
            'message' => 'Failed to update status'
        ]);
    }
}
