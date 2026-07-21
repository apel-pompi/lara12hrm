<?php

namespace App\Services\SocialMedia\ApiService;

use App\Models\SocialMedia\SocialMediaConversation;
use App\Models\SocialMedia\SocialMediaMessage;
use App\Models\SocialMedia\SocialMediaSetup;
use App\Services\SocialMedia\ApiService\MetaApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppApiService extends MetaApiService
{

    public function send(
        SocialMediaConversation $conversation,
        string $message
    ): array {


        $setup = SocialMediaSetup::platform(
            SocialMediaSetup::WHATSAPP
        );

        $response = $this->sendWhatsappMessage(

            accessToken: $setup->access_token,

            phoneNumberId: $setup->phone_number_id,

            phone: $conversation->contact->phone_number,

            message: $message

        );

        return [

            'message_id' => data_get(

                $response,

                'messages.0.id'

            ),

            'response' => $response,

        ];
    }
    /*
|--------------------------------------------------------------------------
| WhatsApp Text Message
|--------------------------------------------------------------------------
*/

    public function sendWhatsappMessage(

        string $accessToken,

        string $phoneNumberId,

        string $phone,

        string $message

    ): array {

        return $this->post(

            $accessToken,

            "{$phoneNumberId}/messages",

            [

                "messaging_product" => "whatsapp",

                "to" => $phone,

                "type" => "text",

                "text" => [

                    "preview_url" => false,

                    "body" => $message

                ]

            ]

        );
    }

    public function sendWhatsappTemplate(

        string $accessToken,

        string $phoneNumberId,

        string $phone,

        string $template,

        string $language = 'en'

    ): array {

        return $this->post(

            $accessToken,

            "{$phoneNumberId}/messages",

            [

                "messaging_product" => "whatsapp",

                "to" => $phone,

                "type" => "template",

                "template" => [

                    "name" => $template,

                    "language" => [

                        "code" => $language

                    ]

                ]

            ]

        );
    }

    public function sendWhatsappImage(

        string $accessToken,

        string $phoneNumberId,

        string $phone,

        string $url,

        ?string $caption = null

    ): array {

        return $this->post(

            $accessToken,

            "{$phoneNumberId}/messages",

            [

                "messaging_product" => "whatsapp",

                "to" => $phone,

                "type" => "image",

                "image" => [

                    "link" => $url,

                    "caption" => $caption

                ]

            ]

        );
    }

    public function sendWhatsappDocument(

        string $accessToken,

        string $phoneNumberId,

        string $phone,

        string $url,

        ?string $filename = null

    ): array {

        return $this->post(

            $accessToken,

            "{$phoneNumberId}/messages",

            [

                "messaging_product" => "whatsapp",

                "to" => $phone,

                "type" => "document",

                "document" => [

                    "link" => $url,

                    "filename" => $filename

                ]

            ]

        );
    }

    public function sendWhatsappVideo(

        string $accessToken,

        string $phoneNumberId,

        string $phone,

        string $url

    ): array {

        return $this->post(

            $accessToken,

            "{$phoneNumberId}/messages",

            [

                "messaging_product" => "whatsapp",

                "to" => $phone,

                "type" => "video",

                "video" => [

                    "link" => $url

                ]

            ]

        );
    }

    public function sendWhatsappAudio(

        string $accessToken,

        string $phoneNumberId,

        string $phone,

        string $url

    ): array {

        return $this->post(

            $accessToken,

            "{$phoneNumberId}/messages",

            [

                "messaging_product" => "whatsapp",

                "to" => $phone,

                "type" => "audio",

                "audio" => [

                    "link" => $url

                ]

            ]

        );
    }

    public function sendWhatsappLocation(

        string $accessToken,

        string $phoneNumberId,

        string $phone,

        float $lat,

        float $lng,

        ?string $name = null,

        ?string $address = null

    ): array {

        return $this->post(

            $accessToken,

            "{$phoneNumberId}/messages",

            [

                "messaging_product" => "whatsapp",

                "to" => $phone,

                "type" => "location",

                "location" => [

                    "latitude" => $lat,

                    "longitude" => $lng,

                    "name" => $name,

                    "address" => $address

                ]

            ]

        );
    }

    public function markWhatsappRead(

        string $accessToken,

        string $phoneNumberId,

        string $messageId

    ): array {

        return $this->post(

            $accessToken,

            "{$phoneNumberId}/messages",

            [

                "messaging_product" => "whatsapp",

                "status" => "read",

                "message_id" => $messageId

            ]

        );
    }

    public function markAsRead(

        SocialMediaMessage $message

    ): array {

        $setup = SocialMediaSetup::platform(

            SocialMediaSetup::WHATSAPP

        );
        // Log::info('MARK READ', [

        //     'phone_number_id' => $setup->phone_number_id,

        //     'meta_message_id' => $message->meta_message_id,

        //     'direction' => $message->direction,

        //     'status' => $message->status,

        // ]);
        return $this->markWhatsappRead(

            accessToken: $setup->access_token,

            phoneNumberId: $setup->phone_number_id,

            messageId: $message->meta_message_id

        );
    }

    public function uploadWhatsappMedia(

        string $accessToken,

        string $phoneNumberId,

        string $filePath,

        string $mimeType

    ): array {

        $response = Http::withToken($accessToken)

            ->attach(

                'file',

                fopen($filePath, 'r'),

                basename($filePath)

            )

            ->post(

                "https://graph.facebook.com/v23.0/{$phoneNumberId}/media",

                [

                    'messaging_product' => 'whatsapp',

                    'type' => $mimeType

                ]

            );

        return $this->response($response);
    }

    public function downloadWhatsappMedia(

        string $accessToken,

        string $mediaId

    ): array {

        return $this->get(

            $accessToken,

            $mediaId

        );
    }

    public function getPhoneNumbers(

        string $accessToken,

        string $wabaId

    ): array {

        return $this->get(

            $accessToken,

            "{$wabaId}/phone_numbers"

        );
    }

    public function getBusinessProfile(

        string $accessToken,

        string $phoneNumberId

    ): array {

        return $this->get(

            $accessToken,

            "{$phoneNumberId}/whatsapp_business_profile"

        );
    }

    public function updateBusinessProfile(

        string $accessToken,

        string $phoneNumberId,

        array $profile

    ): array {

        return $this->post(

            $accessToken,

            "{$phoneNumberId}/whatsapp_business_profile",

            $profile

        );
    }
}
