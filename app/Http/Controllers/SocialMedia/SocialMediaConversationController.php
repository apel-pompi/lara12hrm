<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia\SocialMediaConversation;
use App\Services\SocialMedia\InboxService;
use App\Services\SocialMedia\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialMediaConversationController extends Controller
{

    public function __construct(
        protected InboxService $inboxService,
        protected MessageService $messageService
    ) {}

    public function index(Request $request): JsonResponse
    {


        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $isPrivileged = $user->hasAnyRole(['superadmin', 'Admin', 'Manager']);
        return response()->json(array_merge([
            'success' => true,
        ], $this->inboxService->conversationList($request->all(), $user, $isPrivileged)));
    }

    public function messages(

        SocialMediaConversation $conversation

    ) {

        $this->messageService

            ->markConversationRead(

                $conversation

            );

        return response()->json([

            'data' =>

            $this->inboxService

                ->conversationMessages(

                    $conversation

                ),

        ]);
    }

    public function contactChannels(SocialMediaConversation $conversation)
    {
        $studentId = $conversation->contact->student_id;

        if (!$studentId) {
            return response()->json([
                'data' => []
            ]);
        }

        $channels = SocialMediaConversation::with('contact')

            ->whereHas('contact', function ($q) use ($studentId) {

                $q->where('student_id', $studentId);
            })

            ->get();

        return response()->json([
            'data' => $channels
        ]);
    }

    public function show(SocialMediaConversation $conversation)
    {
        $conversation->load([

            'contact',

            'contact.student',

            'contact.student.assignedUser',

            'contact.student.source',

        ]);

        return response()->json([
            'data' => $conversation
        ]);
    }
}
