<?php

namespace App\Services\SocialMedia;

use App\Events\SocialMedia\MessageStatusUpdated;
use App\Events\SocialMedia\NewMessageReceived;
use App\Models\SocialMedia\SocialMediaContact;
use App\Models\SocialMedia\SocialMediaMessage;
use App\Models\SocialMedia\SocialMediaConversation;
use App\Models\SocialMedia\SocialMediaSetup;
use App\Models\Student\Student;
use App\Services\SocialMedia\ApiService\FacebookApiService;
use App\Services\SocialMedia\ApiService\InstagramApiService;
use App\Services\SocialMedia\ApiService\MessengerApiService;
use App\Services\SocialMedia\ApiService\MetaApiService;
use App\Services\SocialMedia\ApiService\WhatsAppApiService;
use App\Services\SocialMedia\MetaPlatformService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MessageService
{
    protected InboxService $inboxService;
    protected MetaApiService $metaApiService;
    protected WhatsAppApiService $whatsAppApiService;
    protected MessengerApiService $messengerApiService;
    protected InstagramApiService $instagramApiService;
    protected MetaPlatformService $metaPlatformService;
    protected FacebookApiService $facebookApiService;

    public function __construct(

        InboxService $inboxService,

        WhatsAppApiService $whatsAppApiService,

        MessengerApiService $messengerApiService,

        InstagramApiService $instagramApiService,

        MetaPlatformService $metaPlatformService,
        FacebookApiService $facebookApiService,

    ) {

        $this->inboxService = $inboxService;

        $this->whatsAppApiService = $whatsAppApiService;

        $this->messengerApiService = $messengerApiService;

        $this->instagramApiService = $instagramApiService;
        $this->metaPlatformService = $metaPlatformService;
        $this->facebookApiService = $facebookApiService;
    }

    /*
|--------------------------------------------------------------------------
| Receive WhatsApp Message
|--------------------------------------------------------------------------
*/

    public function receiveWhatsapp(Request $request)
    {
        //Log::info('WHATSAPP RECEIVE START');
        //Log::info($request->all());
        $message = data_get(
            $request->all(),
            'entry.0.changes.0.value.messages.0'
        );

        $profile = data_get(
            $request->all(),
            'entry.0.changes.0.value.contacts.0.profile.name'
        );

        $phone = $message['from'];

        $messageId = $message['id'];
        $type = $message['type'] ?? 'text';
        $text = match ($type) {

            'text' => data_get($message, 'text.body'),

            default => null,
        };

        // Log::info([
        //     'phone' => $phone,
        //     'messageId' => $messageId,
        //     'text' => $text,
        // ]);

        $inbox = $this->inboxService->getOrCreate(

            platform: 'whatsapp',

            platformUserId: $phone,

            contactData: [

                'social_name' => $profile,

                'phone_number' => $phone,

            ]

        );
        $this->attachStudentToContact($inbox['contact']);
        $savedMessage = $this->save([

            'conversation_id' => $inbox['conversation']->id,

            'contact_id' => $inbox['contact']->id,

            'platform' => 'whatsapp',

            'meta_message_id' => $messageId,

            'direction' => 'inbound',

            'sender_type' => 'customer',

            'message_type' => $type,

            'message' => $text,

            'attachment' => null,

            'attachment_type' => null,

            'attachment_size' => null,

            'status' => 'received',

            'payload' => $request->all(),

        ]);
        event(

            new \App\Events\SocialMedia\NewMessageReceived(

                $savedMessage

            )

        );
        $this->inboxService->updateConversation(

            $inbox['conversation'],

            $savedMessage

        );

        return response(
            'EVENT_RECEIVED',
            200
        );
    }
    /*
|--------------------------------------------------------------------------
| Receive Messenger Message
|--------------------------------------------------------------------------
*/

    public function receiveMessenger(Request $request): ?SocialMediaMessage
    {
        $payload = $request->all();
        //Log::info('this is payload');
        //Log::info($payload);
        return DB::transaction(function () use ($payload) {

            $event = data_get(
                $payload,
                'entry.0.messaging.0'
            );

            if (!$event) {
                return null;
            }
            if (isset($event['delivery'])) {

                //Log::info('Messenger delivery event', $event);

                $mids = data_get($event, 'delivery.mids', []);

                if (!empty($mids)) {
                    SocialMediaMessage::whereIn('meta_message_id', $mids)
                        ->update([
                            'status' => 'delivered',
                        ]);
                }

                return null;
            }
            $psid = data_get(
                $event,
                'sender.id'
            );

            $pageId = data_get(
                $event,
                'recipient.id'
            );

            $text = data_get(
                $event,
                'message.text'
            );

            $messageId = data_get(
                $event,
                'message.mid'
            );
            if (blank($messageId)) {
                Log::warning('Messenger event without message.mid', $event);
                return null;
            }
            $conversationId = data_get(
                $event,
                'conversation.id'
            );

            /*
        |--------------------------------------------------------------------------
        | Contact + Conversation
        |--------------------------------------------------------------------------
        */

            $result = $this->inboxService->getOrCreate(

                'messenger',

                $psid,

                [

                    'page_id' => $pageId,

                ],

                $conversationId

            );

            $this->attachStudentToContact($result['contact']);
            /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

            $message = SocialMediaMessage::firstOrCreate(

                [

                    'meta_message_id' => $messageId

                ],

                [

                    'conversation_id' => $result['conversation']->id,

                    'contact_id' => $result['contact']->id,

                    'platform' => 'messenger',

                    'direction' => 'inbound',

                    'sender_type' => 'customer',

                    'message_type' => 'text',

                    'message' => $text,

                    'status' => 'received',

                    'payload' => $payload,

                ]

            );

            event(

                new \App\Events\SocialMedia\NewMessageReceived(

                    $message

                )

            );
            $this->inboxService->updateConversation(

                $result['conversation'],

                $message

            );

            return $message;
        });
    }

    public function sendWhatsapp(
        int $conversationId,
        ?string $text,
        ?string $fileUrl = null,
        ?string $fileType = null,
        ?string $filePath = null
    ) {
        $conversation = SocialMediaConversation::with('contact')
            ->findOrFail($conversationId);

        $setup = SocialMediaSetup::where(
            'platform',
            SocialMediaSetup::WHATSAPP
        )->firstOrFail();

        $response = null;
        $messageType = 'text';

        if ($fileUrl && $filePath) {
            $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
            $uploadRes = $this->whatsAppApiService->uploadWhatsappMedia(
                $setup->access_token,
                $setup->phone_number_id,
                $filePath,
                $mimeType
            );
            $mediaId = $uploadRes['id'] ?? null;

            if ($mediaId) {
                if ($fileType === 'image') {
                    $response = $this->whatsAppApiService->sendWhatsappImage(
                        $setup->access_token,
                        $setup->phone_number_id,
                        $conversation->contact->phone_number,
                        $mediaId,
                        $text, // caption
                        true // isId
                    );
                    $messageType = 'image';
                } else {
                    $response = $this->whatsAppApiService->sendWhatsappDocument(
                        $setup->access_token,
                        $setup->phone_number_id,
                        $conversation->contact->phone_number,
                        $mediaId,
                        null,
                        true // isId
                    );
                    $messageType = 'document';
                    
                    if ($text) {
                        $this->whatsAppApiService->sendWhatsappMessage(
                            $setup->access_token,
                            $setup->phone_number_id,
                            $conversation->contact->phone_number,
                            $text
                        );
                    }
                }
            } else {
                 $response = $this->whatsAppApiService->sendWhatsappMessage(
                    $setup->access_token,
                    $setup->phone_number_id,
                    $conversation->contact->phone_number,
                    $text
                );
            }
        } else {
            $response = $this->whatsAppApiService->sendWhatsappMessage(
                $setup->access_token,
                $setup->phone_number_id,
                $conversation->contact->phone_number,
                $text
            );
        }

        $message = SocialMediaMessage::create([
            'conversation_id' => $conversation->id,
            'contact_id'      => $conversation->contact_id,
            'platform'        => 'whatsapp',
            'meta_message_id' => data_get($response, 'messages.0.id'),
            'direction'       => 'outbound',
            'sender_type'     => 'agent',
            'message_type'    => $messageType,
            'message'         => $text,
            'attachment'      => $fileUrl,
            'status'          => 'sent',
            'payload'         => $response,
        ]);

        broadcast(new NewMessageReceived($message))->toOthers();

        return $message;
    }

    public function sendMessenger(
        int $conversationId,
        ?string $text,
        ?string $fileUrl = null,
        ?string $fileType = null,
        ?string $filePath = null
    ) {
        $conversation = SocialMediaConversation::with('contact')
            ->findOrFail($conversationId);

        $setup = SocialMediaSetup::where(
            'platform',
            SocialMediaSetup::MESSENGER
        )
            ->firstOrFail();

        $response = null;
        $messageType = 'text';

        if ($fileUrl && $filePath) {
            $uploadRes = $this->messengerApiService->uploadMessengerMedia(
                $setup->access_token,
                $filePath,
                $fileType === 'image' ? 'image' : 'file'
            );
            $attachmentId = $uploadRes['attachment_id'] ?? null;

            if ($attachmentId) {
                if ($fileType === 'image') {
                    $response = $this->messengerApiService->sendMessengerImage(
                        $setup->access_token,
                        $conversation->contact->platform_user_id,
                        $attachmentId
                    );
                    $messageType = 'image';
                } else {
                    $response = $this->messengerApiService->sendMessengerFile(
                        $setup->access_token,
                        $conversation->contact->platform_user_id,
                        $attachmentId
                    );
                    $messageType = 'file';
                }
                
                if ($text) {
                     $this->messengerApiService->sendMessengerMessage(
                        $setup->access_token,
                        $conversation->contact->platform_user_id,
                        $text
                    );
                }
            } else {
                $response = $this->messengerApiService->sendMessengerMessage(
                    $setup->access_token,
                    $conversation->contact->platform_user_id,
                    $text
                );
            }
        } else {
            $response = $this->messengerApiService->sendMessengerMessage(
                $setup->access_token,
                $conversation->contact->platform_user_id,
                $text
            );
        }

        $message = SocialMediaMessage::create([
            'conversation_id'   => $conversation->id,
            'contact_id'        => $conversation->contact_id,
            'platform'          => 'messenger',
            'meta_message_id'   => $response['message_id'] ?? null,
            'direction'         => 'outbound',
            'sender_type'       => 'agent',
            'message_type'      => $messageType,
            'message'           => $text,
            'attachment'        => $fileUrl,
            'status'            => 'sent',
            'payload'           => $response,
        ]);

        broadcast(new NewMessageReceived($message))->toOthers();

        return $message;
    }
    /**
     * Save Message
     */
    public function save(array $data): SocialMediaMessage
    {
        return DB::transaction(function () use ($data) {

            $message = SocialMediaMessage::firstOrCreate(

                [
                    'meta_message_id' => $data['meta_message_id']
                ],

                [

                    /*
                |--------------------------------------------------------------------------
                | Relations
                |--------------------------------------------------------------------------
                */

                    'conversation_id' => $data['conversation_id'],

                    'contact_id' => $data['contact_id'],

                    /*
                |--------------------------------------------------------------------------
                | Platform
                |--------------------------------------------------------------------------
                */

                    'platform' => $data['platform'],

                    /*
                |--------------------------------------------------------------------------
                | Direction
                |--------------------------------------------------------------------------
                */

                    'direction' => $data['direction'],

                    /*
                |--------------------------------------------------------------------------
                | Sender
                |--------------------------------------------------------------------------
                */

                    'sender_type' => $data['sender_type'],

                    /*
                |--------------------------------------------------------------------------
                | Message
                |--------------------------------------------------------------------------
                */

                    'message_type' => $data['message_type'] ?? 'text',

                    'message' => $data['message'] ?? null,

                    /*
                |--------------------------------------------------------------------------
                | Attachment
                |--------------------------------------------------------------------------
                */

                    'attachment' => $data['attachment'] ?? null,

                    'attachment_type' => $data['attachment_type'] ?? null,

                    'attachment_size' => $data['attachment_size'] ?? null,

                    /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                    'status' => $data['status'] ?? 'received',

                    /*
                |--------------------------------------------------------------------------
                | Payload
                |--------------------------------------------------------------------------
                */

                    'payload' => $data['payload'] ?? null,

                    /*
                |--------------------------------------------------------------------------
                | Date
                |--------------------------------------------------------------------------
                */

                    'sent_at' => $data['sent_at'] ?? now(),

                    'delivered_at' => $data['delivered_at'] ?? null,

                    'read_at' => $data['read_at'] ?? null,

                ]

            );

            return $message->fresh();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Update Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request)
    {
        $status = data_get(
            $request->all(),
            'entry.0.changes.0.value.statuses.0'
        );

        if (!$status) {
            return response('EVENT_RECEIVED', 200);
        }

        $metaMessageId = $status['id'];

        $messageStatus = $status['status'];

        $update = [

            'status' => $messageStatus,

        ];

        switch ($messageStatus) {

            case 'delivered':
                $update['delivered_at'] = now();
                break;

            case 'read':
                $update['read_at'] = now();
                break;
        }

        $message = SocialMediaMessage::where(
            'meta_message_id',
            $metaMessageId
        )
            ->first();
        //Log::info('this is message');
        //Log::info($message);

        $message->update($update);

        event(
            new MessageStatusUpdated(
                $message->fresh()
            )
        );
        return response('EVENT_RECEIVED', 200);
    }

    /*
    |--------------------------------------------------------------------------
    | Mark Conversation Read
    |--------------------------------------------------------------------------
    */
    public function markConversationRead(
        SocialMediaConversation $conversation
    ): void {

        $messages = $conversation->messages()

            ->where('direction', 'inbound')

            ->where('status', 'received')

            ->get();

        foreach ($messages as $message) {

            $this->metaPlatformService->markAsRead(

                $conversation,

                $message

            );

            $message->update([

                'status' => 'read',

                'read_at' => now(),

            ]);
            event(
                new MessageStatusUpdated(
                    $message->fresh()
                )
            );
        }

        $this->inboxService->markConversationAsRead(

            $conversation

        );
    }


    /*
    |--------------------------------------------------------------------------
    | Last Message
    |--------------------------------------------------------------------------
    */

    public function latest(

        int $conversationId

    ) {

        return SocialMediaMessage::where(

            'conversation_id',

            $conversationId

        )

            ->latest()

            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(

        SocialMediaMessage $message

    ): bool {

        return $message->delete();
    }

    private function attachStudentToContact(SocialMediaContact $contact): void
    {

        // Already linked
        if ($contact->student_id) {
            return;
        }

        if (!$contact->phone_number) {
            return;
        }

        $phone = preg_replace('/\D/', '', $contact->phone_number);

        if (str_starts_with($phone, '880')) {
            $phone = '0' . substr($phone, 3);
        }

        $student = Student::where(function ($q) use ($phone) {
            $q->where('phone', $phone)
                ->orWhere('phone', '880' . substr($phone, 1))
                ->orWhere('phone', '+880' . substr($phone, 1));
        })->first();

        if ($student) {
            $contact->update([
                'student_id' => $student->id,
            ]);
        }
    }
}
