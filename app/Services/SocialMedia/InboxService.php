<?php

namespace App\Services\SocialMedia;

use App\Events\SocialMedia\ConversationUpdated;
use App\Models\SocialMedia\SocialMediaContact;
use App\Models\SocialMedia\SocialMediaConversation;
use App\Models\SocialMedia\SocialMediaMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InboxService
{
    /**
     * Create or Return Contact
     */
    public function findOrCreateContact(array $data): SocialMediaContact
    {
        return DB::transaction(function () use ($data) {

            $contact = SocialMediaContact::where(
                'platform',
                $data['platform']
            )
                ->where(
                    'platform_user_id',
                    $data['platform_user_id']
                )
                ->first();

            if ($contact) {

                $contact->update([

                    'social_name' => $data['social_name'] ?? $contact->social_name,

                    'phone_number' => $data['phone_number'] ?? $contact->phone_number,

                    'email' => $data['email'] ?? $contact->email,

                    'profile_picture' => $data['profile_picture'] ?? $contact->profile_picture,

                    'last_seen_at' => now(),

                ]);

                return $contact->fresh();
            }

            return SocialMediaContact::create([

                'student_id' => null,

                'platform' => $data['platform'],

                'platform_user_id' => $data['platform_user_id'],

                'social_name' => $data['social_name'],

                'phone_number' => $data['phone_number'] ?? null,

                'email' => $data['email'] ?? null,

                'profile_picture' => $data['profile_picture'] ?? null,

                'last_seen_at' => now(),

            ]);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Conversation
    |--------------------------------------------------------------------------
    */

    public function findOrCreateConversation(
        SocialMediaContact $contact,
        array $data
    ): SocialMediaConversation {

        return DB::transaction(function () use ($contact, $data) {

            $conversation = SocialMediaConversation::where(
                'contact_id',
                $contact->id
            )
                ->where(
                    'platform',
                    $data['platform']
                )
                ->first();

            if ($conversation) {
                if (
                    empty($conversation->conversation_id)
                    && !empty($data['conversation_id'])
                ) {

                    $conversation->update([
                        'conversation_id' => $data['conversation_id'],
                    ]);
                }

                return $conversation->fresh();
            }

            return SocialMediaConversation::create([

                'contact_id'      => $contact->id,

                'platform'        => $data['platform'],

                'conversation_id' => $data['conversation_id'] ?? null,

                'last_message'    => null,

                'last_message_at' => null,

                'unread_count'    => 0,

                'status'          => true,

            ]);
        });
    }
    /*
    |--------------------------------------------------------------------------
    | Contact + Conversation
    |--------------------------------------------------------------------------
    */

    public function getOrCreate(

        string $platform,

        string $platformUserId,

        array $contactData = [],

        ?string $conversationId = null

    ): array {

        return DB::transaction(function () use (

            $platform,

            $platformUserId,

            $contactData,

            $conversationId

        ) {

            $contact = $this->findOrCreateContact([

                'platform'          => $platform,

                'platform_user_id'  => $platformUserId,

                'social_name'       => $contactData['social_name'] ?? null,

                'phone_number'      => $contactData['phone_number'] ?? null,

                'phone_number_id'   => $contactData['phone_number_id'] ?? null,

                'page_id'           => $contactData['page_id'] ?? null,

                'email'             => $contactData['email'] ?? null,

                'profile_picture'   => $contactData['profile_picture'] ?? null,

            ]);

            $conversation = $this->findOrCreateConversation(

                $contact,

                [

                    'platform'        => $platform,

                    'conversation_id' => $conversationId,

                ]

            );

            return [

                'contact'      => $contact,

                'conversation' => $conversation,

            ];
        });
    }

    public function conversationList()
    {


        return SocialMediaConversation::query()

            ->with([

                'contact.student'

            ])

            ->latest('last_message_at')

            ->get()

            ->map(function ($conversation) {
                $lastMessage = $conversation->messages()
                    ->latest()
                    ->first();
                return [

                    'id' => $conversation->id,

                    'platform' => $conversation->platform,

                    'student_id' => optional(
                        $conversation->contact
                    )->student_id,

                    'student_name' => optional(
                        optional($conversation->contact)->student
                    )->fname . ' ' .
                        optional(
                            optional($conversation->contact)->student
                        )->lname,

                    'social_name' => optional(
                        $conversation->contact
                    )->social_name,

                    'phone' => optional(
                        $conversation->contact
                    )->phone_number,

                    'profile_picture' => optional(
                        $conversation->contact
                    )->profile_picture,

                    'last_message' => $conversation->last_message,

                    'last_message_at' => $conversation->last_message_at,

                    'unread_count' => $conversation->unread_count,
                    'last_message_status' => optional($lastMessage)->status,

                    'last_message_direction' => optional($lastMessage)->direction,

                    'last_message_type' => optional($lastMessage)->message_type,

                ];
            });
    }

    public function conversationMessages(
        SocialMediaConversation $conversation
    ) {

        return $conversation

            ->messages()

            ->orderBy('created_at')

            ->get()

            ->map(function ($message) {

                return [

                    'id' => $message->id,

                    'direction' => $message->direction,

                    'sender_type' => $message->sender_type,

                    'message_type' => $message->message_type,

                    'message' => $message->message,

                    'attachment' => $message->attachment,

                    'status' => $message->status,

                    'created_at' => $message->created_at,

                ];
            });
    }
    /*
    |--------------------------------------------------------------------------
    | Update Conversation
    |--------------------------------------------------------------------------
    */

    public function updateConversation(
        SocialMediaConversation $conversation,
        SocialMediaMessage $message
    ): void {

        $conversation->update([

            'last_message'    => $message->message,

            'last_message_at' => $message->created_at,

            'unread_count'    => $message->direction === 'inbound'
                ? $conversation->unread_count + 1
                : $conversation->unread_count,

        ]);

        event(
            new ConversationUpdated(
                $conversation->fresh()
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Read
    |--------------------------------------------------------------------------
    */

    public function markConversationAsRead(

        SocialMediaConversation $conversation

    ): void {

        $conversation->update([

            'unread_count' => 0

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Assign Student
    |--------------------------------------------------------------------------
    */

    public function assignStudent(

        SocialMediaContact $contact,

        int $studentId

    ): void {

        $contact->update([

            'student_id' => $studentId

        ]);
    }
}
