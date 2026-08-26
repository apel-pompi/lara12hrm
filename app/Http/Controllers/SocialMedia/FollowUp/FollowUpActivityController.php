<?php

namespace App\Http\Controllers\SocialMedia\FollowUp;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowUpActivity\StoreFollowUpActivityRequest;
use App\Models\SocialMedia\FollowUp\FollowUpActivity;
use App\Models\SocialMedia\FollowUp\FollowUpMaster;
use App\Models\SocialMedia\FollowUp\FollowUpStatus;
use App\Models\User;
use App\Services\SocialMedia\FollowUp\FollowUpActivityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class FollowUpActivityController extends Controller
{
    public function __construct(
        protected FollowUpActivityService $activityService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(
            FollowUpActivity::with([
                'student',
                'master',
                'status',
                'creator',
                'assignedTo',
            ])
                ->latest()
                ->paginate(10)
        );
    }



    public function create(Request $request): Response
    {
        $studentId = $request->integer('student_id');

        return Inertia::render('allpages/Agency/MetaChat/FollowUpComponents/CreateModal', [
            'studentId' => $studentId,

            'masters' => FollowUpMaster::query()
                ->where('status', true)
                ->orderBy('sort_order')
                ->get([
                    'id',
                    'code',
                    'name',
                    'icon',
                    'color',
                ]),

            'statuses' => FollowUpStatus::query()
                ->where('status', true)
                ->orderBy('sort_order')
                ->get([
                    'id',
                    'code',
                    'name',
                    'color',
                    'icon',
                    'is_completed',
                    'is_cancelled',
                    'allow_reschedule',
                ]),

            'users' => User::query()
                ->whereNull('banned_at')
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                ]),
        ]);
    }

    public function student(int $studentId): JsonResponse
    {
        return response()->json(
            FollowUpActivity::with([
                'master',
                'status',
                'creator',
                'assignedTo',
                'reminders'
            ])
                ->where('student_id', $studentId)
                ->latest()
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFollowUpActivityRequest $request)
    {
        $activity = $this->activityService->create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Follow-up activity created successfully.',
            'data' => $activity->load([
                'student',
                'master',
                'status',
                'creator',
                'assignedTo',
                'reminders',
            ]),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FollowUpActivity $activity)
    {

        $activity->load([
            'student',
            'master',
            'status',
            'creator',
            'assignedTo',
            'reminders',
        ]);

        return Inertia::render(
            'allpages/Agency/MetaChat/FollowUpComponents/FollowUpActivityShow',
            [
                'activity' => $activity,
            ]
        );
    }

    public function showModalActivity(FollowUpActivity $activity)
    {
        $activity->load([
            'student',
            'master',
            'status',
            'creator',
            'assignedTo',
            'reminders',
        ]);

        return response()->json([
            'success' => true,
            'data' => $activity,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        FollowUpActivity $followUpActivity
    ): JsonResponse {
        $followUpActivity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Activity deleted successfully.'
        ]);
    }
}
