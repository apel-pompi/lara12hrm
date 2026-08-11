<?php

namespace App\Http\Controllers\SocialMedia\FollowUp;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowUpStatus\StoreFollowUpStatusRequest;
use App\Http\Requests\FollowUpStatus\UpdateFollowUpStatusRequest;
use App\Models\SocialMedia\FollowUp\FollowUpStatus;
use Illuminate\Http\JsonResponse;

class FollowUpStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(
            FollowUpStatus::orderBy('sort_order')
                ->get()
        );
    }

    public function active(): JsonResponse
    {
        return response()->json(
            FollowUpStatus::where('status', true)
                ->orderBy('sort_order')
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreFollowUpStatusRequest $request
    ): JsonResponse {
        $status = FollowUpStatus::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Follow-up status created successfully.',
            'data' => $status,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        FollowUpStatus $followUpStatus
    ): JsonResponse {
        return response()->json($followUpStatus);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateFollowUpStatusRequest $request,
        FollowUpStatus $followUpStatus
    ): JsonResponse {
        $followUpStatus->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Follow-up status updated successfully.',
            'data' => $followUpStatus,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        FollowUpStatus $followUpStatus
    ): JsonResponse {
        $followUpStatus->delete();

        return response()->json([
            'success' => true,
            'message' => 'Follow-up status deleted successfully.',
        ]);
    }
}
