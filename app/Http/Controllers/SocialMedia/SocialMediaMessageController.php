<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia\SocialMediaConversation;
use App\Services\SocialMedia\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialMediaMessageController extends Controller
{

    public function __construct(
        protected MessageService $messageService
    ) {}

    public function send(Request $request)
    {

        $conversation = SocialMediaConversation::findOrFail(
            $request->conversation_id
        );
        $request->validate([
            'conversation_id' => 'required|integer',
            'message' => 'required|string',
        ]);

        switch ($conversation->platform) {

            case 'messenger':
                return $this->messageService->sendMessenger(
                    $conversation->id,
                    $request->message
                );

            case 'whatsapp':
                return $this->messageService->sendWhatsapp(
                    $conversation->id,
                    $request->message
                );

            default:
                throw new \Exception('Unsupported platform');
        }
    }
}
