<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacebookController extends Controller
{
    public function verify(Request $request)
    {
        if (
            $request->hub_mode === 'subscribe' &&
            $request->hub_verify_token === 'your_verify_token'
        ) {
            return response($request->hub_challenge, 200);
        }

        return response('Invalid', 403);
    }

    public function handle(Request $request)
    {
        $data = $request->all();

        // Lead ID collect
        $leadId = $data['entry'][0]['changes'][0]['value']['leadgen_id'];

        $accessToken = 'YOUR_ACCESS_TOKEN';
        $response = Http::get("https://graph.facebook.com/v19.0/$leadId", [
            'access_token' => $accessToken,
        ]);

        $leadData = $response->json();
        dd($leadData);
    }
}
