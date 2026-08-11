<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use App\Models\SocialMedia\SocialMediaSetup;
use App\Models\SocialMedia\FacebookForm;
use App\Models\SocialMedia\UserWiseForm;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class FacebookController extends Controller
{
    use AuthorizesRequests;

    public function facebookForm()
    {
        try {
            $this->authorize('facebookForm.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $pages = SocialMediaSetup::where('platform', 'facebook')->get();
        $forms = FacebookForm::orderByDesc('id')->get();

        return Inertia::render('allpages/default/facebook-form', [
            'pageTitle' => 'Facebook Form',
            'pages' => $pages,
            'forms' => $forms,
        ]);
    }

    public function syncFacebookForms(Request $request)
    {
        try {
            $this->authorize('facebookForm.sync');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $pageId = $request->input('page_id');
        $pageToken = SocialMediaSetup::where('page_id', $pageId)
            ->where('platform', 'facebook')
            ->value('access_token');
        $response = Http::get(
            "https://graph.facebook.com/v23.0/{$pageId}/leadgen_forms",
            [
                'fields' => 'id,name,status,created_time',
                'access_token' => $pageToken
            ]
        );
        // if ($response->failed()) {
        //     Log::error('Meta API Error', $response->json());
        // } else {
        //     Log::info('Raw Meta Response', $response->json());
        // }
        $forms = $response->json('data', []);

        foreach ($forms as $form) {
            $createdTime = null;
            if (!empty($form['created_time'])) {
                try {
                    $createdTime = Carbon::parse($form['created_time'])->toDateTimeString();
                } catch (\Exception $e) {
                    // Fallback: strip timezone and replace T with space
                    $createdTime = preg_replace('/T/', ' ', preg_replace('/\+.*$/', '', $form['created_time']));
                }
            }

            FacebookForm::updateOrInsert(
                ['facebook_form_id' => $form['id']],
                [
                    'form_name' => $form['name'],
                    'status' => $form['status'] ?? null,
                    'created_time' => $createdTime,
                    'page_id' => $pageId,
                    'updated_at' => now(),
                ]
            );
        }
        return [
            'success' => true,
            'total_forms' => count($forms)
        ];
    }

    public function deleteFacebookForm(FacebookForm $formId)
    {
        try {
            $this->authorize('facebookForm.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $formId->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Facebook form.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
