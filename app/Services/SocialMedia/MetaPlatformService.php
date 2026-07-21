<?php

namespace App\Services\SocialMedia;

use App\Models\SocialMedia\SocialMediaConversation;
use App\Models\SocialMedia\SocialMediaMessage;
use App\Models\SocialMedia\SocialMediaSetup;
use App\Services\SocialMedia\ApiService\WhatsAppApiService;
use App\Services\SocialMedia\ApiService\MessengerApiService;

class MetaPlatformService
{
    public function __construct(
        protected WhatsAppApiService $whatsapp,
        protected MessengerApiService $messenger,
    ) {}

    public function markAsRead(
        SocialMediaConversation $conversation,
        SocialMediaMessage $message
    ) {
        return match ($conversation->platform) {

            SocialMediaSetup::WHATSAPP =>
            $this->whatsapp->markAsRead($message),

            SocialMediaSetup::MESSENGER =>
            $this->messenger->markAsRead($message),

            default => throw new \Exception('Platform Not Supported'),
        };
    }
}
