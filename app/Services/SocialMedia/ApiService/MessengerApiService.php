<?php

namespace App\Services\SocialMedia\ApiService;

use App\Models\SocialMedia\SocialMediaConversation;
use App\Models\SocialMedia\SocialMediaMessage;
use App\Models\SocialMedia\SocialMediaSetup;
use App\Services\SocialMedia\ApiService\MetaApiService;
use Illuminate\Support\Facades\Log;

class MessengerApiService extends MetaApiService
{

    public function send(
        SocialMediaConversation $conversation,
        string $message
    ): array {

        $setup = SocialMediaSetup::where(
            'platform',
            SocialMediaSetup::FACEBOOK
        )
            ->where(
                'page_id',
                $conversation->contact->page_id
            )
            ->firstOrFail();

        $response = $this->sendMessengerMessage(

            pageToken: $setup->access_token,

            psid: $conversation->contact->platform_user_id,

            message: $message

        );
        // Log::info('META RESPONSE', [
        //     'PSID' => $conversation->contact->platform_user_id,
        //     'PAGE' => $conversation->contact->page_id,
        // ]);
        return [

            'message_id' => data_get($response, 'message_id'),

            'response' => $response,

        ];
    }
    /*
|--------------------------------------------------------------------------
| Messenger Send Message
|--------------------------------------------------------------------------
*/

    public function sendMessengerMessage(

        string $pageToken,

        string $psid,

        string $message

    ): array {

        // Log::info('META SEND REQUEST', [
        //     'token' => $pageToken,
        //     'psid' => $psid,
        //     'message' => $message,
        // ]);
        return $this->post(

            $pageToken,

            'me/messages',

            [

                'recipient' => [

                    'id' => $psid

                ],

                'message' => [

                    'text' => $message

                ]

            ]

        );
    }

    /*
|--------------------------------------------------------------------------
| Messenger User Profile
|--------------------------------------------------------------------------
*/

    public function getMessengerProfile(

        string $pageToken,

        string $psid

    ): array {

        return $this->get(

            $pageToken,

            $psid,

            [

                'fields' =>

                'first_name,last_name,profile_pic'

            ]

        );
    }

    /*
|--------------------------------------------------------------------------
| Page Conversations
|--------------------------------------------------------------------------
*/

    public function getMessengerConversations(

        string $pageToken,

        string $pageId

    ): array {

        return $this->get(

            $pageToken,

            "{$pageId}/conversations"

        );
    }

    /*
|--------------------------------------------------------------------------
| Conversation
|--------------------------------------------------------------------------
*/

    public function getMessengerConversation(

        string $pageToken,

        string $conversationId

    ): array {

        return $this->get(

            $pageToken,

            $conversationId

        );
    }

    /*
|--------------------------------------------------------------------------
| Conversation Messages
|--------------------------------------------------------------------------
*/

    public function getMessengerMessages(

        string $pageToken,

        string $conversationId

    ): array {

        return $this->get(

            $pageToken,

            "{$conversationId}/messages"

        );
    }

    /*
|--------------------------------------------------------------------------
| Conversation Participants
|--------------------------------------------------------------------------
*/

    public function getMessengerParticipants(

        string $pageToken,

        string $conversationId

    ): array {

        return $this->get(

            $pageToken,

            $conversationId,

            [

                'fields' => 'participants'

            ]

        );
    }

    /*
|--------------------------------------------------------------------------
| Mark Seen
|--------------------------------------------------------------------------
*/
    public function markMessengerRead(
        string $pageToken,
        string $psid
    ): array {

        return $this->post(

            $pageToken,

            'me/messages',

            [

                'recipient' => [

                    'id' => $psid

                ],

                'sender_action' => 'mark_seen'

            ]

        );
    }

    public function markAsRead(
        SocialMediaMessage $message
    ): array {

        $setup = SocialMediaSetup::platform(
            SocialMediaSetup::MESSENGER
        );

        return $this->markMessengerRead(

            pageToken: $setup->access_token,

            psid: $message->conversation->contact->platform_user_id

        );
    }

    /*
|--------------------------------------------------------------------------
| Typing ON
|--------------------------------------------------------------------------
*/

    public function messengerTypingOn(

        string $pageToken,

        string $psid

    ): array {

        return $this->post(

            $pageToken,

            'me/messages',

            [

                'recipient' => [

                    'id' => $psid

                ],

                'sender_action' => 'typing_on'

            ]

        );
    }

    /*
|--------------------------------------------------------------------------
| Typing OFF
|--------------------------------------------------------------------------
*/

    public function messengerTypingOff(

        string $pageToken,

        string $psid

    ): array {

        return $this->post(

            $pageToken,

            'me/messages',

            [

                'recipient' => [

                    'id' => $psid

                ],

                'sender_action' => 'typing_off'

            ]

        );
    }

    /*
|--------------------------------------------------------------------------
| Messenger Image
|--------------------------------------------------------------------------
*/

    public function sendMessengerImage(

        string $pageToken,

        string $psid,

        string $url

    ): array {

        return $this->post(

            $pageToken,

            'me/messages',

            [

                'recipient' => [

                    'id' => $psid

                ],

                'message' => [

                    'attachment' => [

                        'type' => 'image',

                        'payload' => [

                            'url' => $url,

                            'is_reusable' => true

                        ]

                    ]

                ]

            ]

        );
    }
    /*
|--------------------------------------------------------------------------
| Messenger File
|--------------------------------------------------------------------------
*/

    public function sendMessengerFile(

        string $pageToken,

        string $psid,

        string $url

    ): array {

        return $this->post(

            $pageToken,

            'me/messages',

            [

                'recipient' => [

                    'id' => $psid

                ],

                'message' => [

                    'attachment' => [

                        'type' => 'file',

                        'payload' => [

                            'url' => $url

                        ]

                    ]

                ]

            ]

        );
    }
}
