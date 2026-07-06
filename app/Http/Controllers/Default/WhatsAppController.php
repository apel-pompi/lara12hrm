<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Default\SocialMediaSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    public function verify(Request $request)
    {
        Log::info('WHATSAPP VERIFY', $request->all());

        $token = $request->get('hub_verify_token');

        $exists = SocialMediaSetup::where(
            'verify_token',
            $token
        )->exists();

        if (
            $request->get('hub_mode') === 'subscribe' &&
            $exists
        ) {
            return response($request->get('hub_challenge'), 200)
                ->header('Content-Type', 'text/plain');
        }

        return response('Invalid verify token', 403);
    }

    public function handle(Request $request)
    {
        try {

            Log::info('WHATSAPP WEBHOOK', $request->all());

            $message = data_get(
                $request->all(),
                'entry.0.changes.0.value.messages.0'
            );

            if (!$message) {
                return response('EVENT_RECEIVED', 200);
            }

            $phone = $message['from'] ?? null;

            $text = data_get(
                $message,
                'text.body'
            );

            $profileName = data_get(
                $request->all(),
                'entry.0.changes.0.value.contacts.0.profile.name'
            );
            Log::info('WHATSAPP MESSAGE', [
                'name' => $profileName,
                'phone' => $phone,
                'message' => $text,
            ]);
            // $this->saveLead([
            //     'name' => $profileName,
            //     'phone' => $phone,
            //     'message' => $text,
            // ]);
        } catch (\Throwable $e) {

            Log::error(
                'WHATSAPP ERROR: ' .
                    $e->getMessage()
            );
        }

        return response(
            'EVENT_RECEIVED',
            200
        );
    }
}
