<?php

namespace App\Services\SocialMedia\ApiService;

use App\Models\SocialMedia\SocialMediaConversation;
use App\Services\SocialMedia\ApiService\MetaApiService;

class InstagramApiService extends MetaApiService
{

    public function send(
        SocialMediaConversation $conversation,
        string $message
    ): array {

        $setup = $this->getSetup('messenger');

        $response = $this->sendMessage(

            token: $setup->access_token,

            instagramId: $conversation->sender_id,

            message: $message

        );

        return [

            'message_id' => data_get($response, 'messages.0.id'),

            'response'   => $response,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Send DM
    |--------------------------------------------------------------------------
    */

    public function sendMessage(string $token, string $instagramId, string $message): array
    {

        return $this->post(

            $token,

            "{$instagramId}/messages",

            [

                "messaging_product" => "instagram",

                "recipient_id" => $instagramId,

                "message" => [

                    "text" => $message

                ]

            ]

        );
    }
}
