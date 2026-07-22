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
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240',
            'image' => 'nullable|image|max:10240',
        ]);

        $fileUrl = null;
        $fileType = null;
        $filePath = null;
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('metachat/images', 'public');
            $fileUrl = asset('storage/' . $path);
            $filePath = storage_path('app/public/' . $path);
            $fileType = 'image';
        } elseif ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('metachat/attachments', 'public');
            $fileUrl = asset('storage/' . $path);
            $filePath = storage_path('app/public/' . $path);
            $fileType = 'document';
        }

        $message = match ($conversation->platform) {
            'messenger' => $this->messageService->sendMessenger(
                $conversation->id,
                $request->message,
                $fileUrl,
                $fileType,
                $filePath
            ),
            'whatsapp' => $this->messageService->sendWhatsapp(
                $conversation->id,
                $request->message,
                $fileUrl,
                $fileType,
                $filePath
            ),
            default => throw new \Exception('Unsupported platform'),
        };

        return response()->json([
            'success' => true,
            'data' => [
                'id'         => $message->id,
                'direction'  => $message->direction,
                'sender_type'=> $message->sender_type,
                'message_type'=> $message->message_type,
                'message'    => $message->message,
                'attachment' => $message->attachment,
                'status'     => $message->status,
                'created_at' => $message->created_at,
            ],
        ]);
    }
}
