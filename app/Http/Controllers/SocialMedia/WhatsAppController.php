<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia\SocialMediaSetup;
use App\Models\SocialMedia\WhatsAppsNumber;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Inertia\Inertia;

class WhatsAppController extends Controller
{

    use AuthorizesRequests;

    public function whatsappSync()
    {
        try {
            $this->authorize('whatsappSync.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $pages = SocialMediaSetup::where('platform', 'whatsapp')->get();
        $numbers = WhatsAppsNumber::all();

        return Inertia::render('allpages/default/whatsapp-sync', [
            'pageTitle' => 'Whatsapp Sync',
            'pages' => $pages,
            'numbers' => $numbers,
        ]);
    }

    public function syncWhatsAppNumbers(Request $request)
    {
        try {
            $this->authorize('whatsappSync.sync');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $wabaId = $request->input('waba_id');
        $setup = SocialMediaSetup::where('whatsapp_business_account_id', $wabaId)
            ->where('platform', 'whatsapp')
            ->first();

        if (!$setup || !$setup->access_token) {
            return response()->json([
                'success' => false,
                'message' => 'WhatsApp setup or access token not found.',
            ], 422);
        }

        $response = Http::get(
            "https://graph.facebook.com/v23.0/{$wabaId}/phone_numbers",
            [
                'fields' => 'id,display_phone_number,verified_name,code_verification_status,quality_rating',
                'access_token' => $setup->access_token,
            ]
        );

        if (!$response->successful()) {
            Log::error('WhatsApp Graph API Error', [
                'waba_id' => $wabaId,
                'response' => $response->json(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to sync WhatsApp numbers.',
            ], $response->status());
        }

        $numbers = $response->json('data', []);

        foreach ($numbers as $number) {
            WhatsAppsNumber::updateOrInsert(
                ['phone_id' => $number['id']],
                [
                    'waba_id' => $wabaId,
                    'phoneno' => $number['display_phone_number'] ?? $number['id'],
                    'verified_name' => $number['verified_name'] ?? null,
                    'status' => $number['code_verification_status'] ?? $number['quality_rating'] ?? null,
                    'updated_at' => now(),
                ]
            );
        }

        return [
            'success' => true,
            'total_numbers' => count($numbers),
        ];
    }

    public function deleteWhatsAppNumber(WhatsAppsNumber $numberId)
    {
        try {
            $this->authorize('whatsappSync.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $numberId->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete WhatsApp number.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
