<?php

namespace App\Http\Controllers\SocialMedia\FollowUp;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowUpMaster\StoreFollowUpMasterRequest;
use App\Http\Requests\FollowUpMaster\UpdateFollowUpMasterRequest;
use App\Models\SocialMedia\FollowUp\FollowUpMaster;
use App\Models\SocialMedia\UserWiseForm;
use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowUpMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FollowUpMaster::query();
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q
                    ->where('code', 'like', "%{$request->search}%")
                    ->orWhere('name', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        $masters = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(
                $request->integer('per_page', 20)
            );

        return response()->json($masters);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFollowUpMasterRequest $request)
    {
        DB::beginTransaction();

        try {
            $master = FollowUpMaster::create(
                $request->validated()
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Follow-up Type created successfully.',
                'data' => $master,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FollowUpMaster $followUpMaster)
    {
        return response()->json($followUpMaster);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFollowUpMasterRequest $request, FollowUpMaster $followUpMaster)
    {
        DB::beginTransaction();
        try {
            $followUpMaster->update(
                $request->validated()
            );
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Follow-up Type updated successfully.',
                'data' => $followUpMaster->fresh(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FollowUpMaster $followUpMaster)
    {
        DB::beginTransaction();

        try {
            $followUpMaster->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Follow-up Type deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function active()
    {
        return response()->json(
            FollowUpMaster::where('status', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
        );
    }

    public function userlist($userId, $student_id)
    {
        $student = Student::find($student_id);

        if (!$student?->form_id) {
            return response()->json([]);
        }

        $form = UserWiseForm::where('form_id', $student->form_id)
            ->where('team_id', $userId)
            ->first();

        if (!$form) {
            return response()->json([]);
        }

        $counselorIds = json_decode($form->counsilor_id, true);
        return response()->json(
            User::select('id', 'name')
                ->whereNull('banned_at')
                ->whereIn('id', $counselorIds)
                ->get()
        );
    }
}
