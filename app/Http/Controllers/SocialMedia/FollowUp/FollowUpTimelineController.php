<?php

namespace App\Http\Controllers\SocialMedia\FollowUp;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia\FollowUp\FollowUpTimeline;
use App\Http\Requests\StoreFollowUpTimelineRequest;
use App\Http\Requests\UpdateFollowUpTimelineRequest;
use App\Models\SocialMedia\FollowUp\FollowUpActivity;
use Illuminate\Http\JsonResponse;

class FollowUpTimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FollowUpActivity $followUpActivity): JsonResponse
    {
        $timeline = $followUpActivity
            ->timelines()
            ->with([
                'user:id,name',
            ])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $timeline,
        ]);
    }

    public function student(
        int $studentId
    ): JsonResponse {

        $timeline = FollowUpTimeline::with([
            'activity.master',
            'activity.status',
            'user:id,name',
        ])
            ->where('student_id', $studentId)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $timeline,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFollowUpTimelineRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FollowUpTimeline $followUpTimeline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FollowUpTimeline $followUpTimeline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFollowUpTimelineRequest $request, FollowUpTimeline $followUpTimeline)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FollowUpTimeline $followUpTimeline)
    {
        //
    }
}
