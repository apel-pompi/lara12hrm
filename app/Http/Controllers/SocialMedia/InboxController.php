<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia\SocialMediaConversation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InboxController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        try {
            $this->authorize('metachat.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $user = Auth::user();

        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $isPrivileged = $user->hasAnyRole(['superadmin', 'Admin', 'Manager']);

        $baseQuery = SocialMediaConversation::query();
        if (! $isPrivileged) {
            $baseQuery->whereHas('contact.student', function ($q) use ($user) {
                $q->where('assain_user', $user->id);
            });
        }

        $countsData = (clone $baseQuery)
            ->leftJoin('social_media_contacts', 'social_media_conversations.contact_id', '=', 'social_media_contacts.id')
            ->leftJoin('students', 'social_media_contacts.student_id', '=', 'students.id')
            ->selectRaw("
                COUNT(social_media_conversations.id) as total,
                SUM(social_media_conversations.unread_count > 0) as unread,
                SUM(social_media_conversations.platform = 'whatsapp') as whatsapp,
                SUM(social_media_conversations.platform = 'messenger') as messenger,
                SUM(social_media_conversations.platform = 'instagram') as instagram,
                SUM(students.status IS NULL) as status_pending,
                SUM(students.status = 1) as status_lead,
                SUM(students.status = 2) as status_prospect,
                SUM(students.status = 3) as status_onboard,
                SUM(students.status = 4) as status_archive
            ")
            ->first();

        $counts = [
            'smartViews' => [
                'all' => (int) ($countsData->total ?? 0),
                'new_leads' => 0,
                'my_leads' => 0,
                'waiting' => 0,
                'unread' => (int) ($countsData->unread ?? 0),
                'priority' => 0,
            ],
            'channels' => [
                'whatsapp' => (int) ($countsData->whatsapp ?? 0),
                'messenger' => (int) ($countsData->messenger ?? 0),
                'instagram' => (int) ($countsData->instagram ?? 0),
            ],
            'status' => [
                'pending' => (int) ($countsData->status_pending ?? 0),
                'lead' => (int) ($countsData->status_lead ?? 0),
                'prospect' => (int) ($countsData->status_prospect ?? 0),
                'onboard' => (int) ($countsData->status_onboard ?? 0),
                'archive' => (int) ($countsData->status_archive ?? 0),
            ],
            'total' => (int) ($countsData->total ?? 0),
        ];

        return Inertia::render('allpages/Agency/MetaChat/metachat', [
            'conversations' => (clone $baseQuery)->with([
                'contact.student',
                'contact',
                'employee',
                'lastMessage'
            ])
                ->when($request->filled('search'), function ($q) use ($request) {
                    $search = $request->search;
                    $q->where(function ($query) use ($search) {
                        $query->where('last_message', 'like', "%{$search}%")
                            ->orWhereHas('contact', function ($contact) use ($search) {
                                $contact->where('phone_number', 'like', "%{$search}%");
                            })
                            ->orWhereHas('contact.student', function ($student) use ($search) {
                                $student->where('fname', 'like', "%{$search}%")
                                    ->orWhere('lname', 'like', "%{$search}%")
                                    ->orWhereRaw("CONCAT(fname,' ',lname) LIKE ?", ["%{$search}%"])
                                    ->orWhere('phone', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
                })
                ->latest('last_message_at')
                ->get(),

            'filters' => $request->only('search'),
            'counts' => $counts,
        ]);
    }
}
