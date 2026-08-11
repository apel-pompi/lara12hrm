<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia\SocialMediaSetup;
use App\Services\SocialMedia\ApiService\FacebookApiService;
use App\Services\SocialMedia\InboxService;
use App\Services\SocialMedia\LeadService;
use App\Services\SocialMedia\MessageService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class MetaWebhookController extends Controller
{
    public function __construct(
        protected FacebookApiService $facebookApi,
        protected LeadService $leadService,
        protected InboxService $inboxService,
        protected MessageService $messageService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Verify
    |--------------------------------------------------------------------------
    */

    public function verify(Request $request)
    {
        $token = $request->hub_verify_token;

        $exists = SocialMediaSetup::where(
            'verify_token',
            $token
        )->exists();

        if (
            $request->hub_mode == 'subscribe'
            && $exists
        ) {

            return response(
                $request->hub_challenge,
                200
            )->header(
                'Content-Type',
                'text/plain'
            );
        }

        return response(
            'Invalid verify token',
            403
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Webhook
    |--------------------------------------------------------------------------
    */

    public function handle(Request $request)
    {
        Log::info('META WEBHOOK', $request->all());

        try {

            /*
        |--------------------------------------------------------------------------
        | WhatsApp Message
        |--------------------------------------------------------------------------
        */

            if (data_get($request->all(), 'entry.0.changes.0.field') === 'messages') {

                $value = data_get(
                    $request->all(),
                    'entry.0.changes.0.value'
                );

                /*
            |--------------------------------------------------------------------------
            | Incoming Customer Message
            |--------------------------------------------------------------------------
            */

                if (!empty($value['messages'])) {

                    return $this->messageService
                        ->receiveWhatsapp($request);
                }

                /*
            |--------------------------------------------------------------------------
            | Message Status
            |--------------------------------------------------------------------------
            */

                if (!empty($value['statuses'])) {

                    return $this->messageService
                        ->updateStatus($request);
                }
            }

            /*
        |--------------------------------------------------------------------------
        | Messenger
        |--------------------------------------------------------------------------
        */

            if (data_get($request->all(), 'entry.0.messaging.0')) {

                return $this->messageService
                    ->receiveMessenger($request);
            }

            /*
        |--------------------------------------------------------------------------
        | Facebook Lead Ads
        |--------------------------------------------------------------------------
        */

            if (
                data_get(
                    $request->all(),
                    'entry.0.changes.0.field'
                ) === 'leadgen'
            ) {

                return $this->leadService
                    ->receiveLead($request);
            }

            /*
        |--------------------------------------------------------------------------
        | Instagram (Future)
        |--------------------------------------------------------------------------
        */
        } catch (\Throwable $e) {

            Log::error('META WEBHOOK ERROR', [

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

                'payload' => $request->all(),

            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return response('EVENT_RECEIVED', 200);
    }
}
