<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia\SocialMediaConversation;
use App\Models\Student\Student;
use App\Services\Agency\Student\StudentPending;
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
        $query = SocialMediaConversation::with([
            'contact.student',
            'contact',
            'employee',
            'lastMessage'
        ]);

        $query->when($request->filled('search'), function ($q) use ($request) {

            $search = $request->search;

            $q->where(function ($query) use ($search) {

                // Last Message
                $query->where('last_message', 'like', "%{$search}%")

                    // Contact
                    ->orWhereHas('contact', function ($contact) use ($search) {

                        $contact->where('phone_number', 'like', "%{$search}%");
                    })

                    // Student
                    ->orWhereHas('contact.student', function ($student) use ($search) {

                        $student->where('fname', 'like', "%{$search}%")
                            ->orWhere('lname', 'like', "%{$search}%")
                            ->orWhereRaw("CONCAT(fname,' ',lname) LIKE ?", ["%{$search}%"])
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        });

        return Inertia::render('allpages/Agency/MetaChat/metachat', [
            'conversations' => $query
                ->latest('last_message_at')
                ->get(),

            'filters' => $request->only('search'),
        ]);
    }
}
