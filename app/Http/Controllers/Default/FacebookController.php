<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Student\Student;
use App\Models\Default\SocialMediaSetup;
use App\Models\Default\FacebookForm;
use App\Models\Default\UserWiseForm;
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

    public function verify(Request $request)
    {
        $token = $request->get('hub_verify_token');

        $exists = SocialMediaSetup::where(
            'verify_token',
            $token
        )->exists();

        if (
            $request->get('hub_mode') === 'subscribe' &&
            $exists
        ) {
            return response(
                $request->get('hub_challenge'),
                200
            );
        }

        return response('Invalid verify token', 403);
    }

    public function handle(Request $request)
    {
        try {

            $leadId = data_get(
                $request->all(),
                'entry.0.changes.0.value.leadgen_id'
            );

            if (!$leadId) {
                return response('EVENT_RECEIVED', 200);
            }

            $formId = data_get(
                $request->all(),
                'entry.0.changes.0.value.form_id'
            );

            $pageId = data_get(
                $request->all(),
                'entry.0.changes.0.value.page_id'
            );

            $pageToken = SocialMediaSetup::where(
                'page_id',
                $pageId
            )->value('access_token');

            if (!$pageToken) {

                Log::warning('Facebook Page Token Not Found', [
                    'page_id' => $pageId,
                    'lead_id' => $leadId,
                ]);

                return response('EVENT_RECEIVED', 200);
            }

            $response = Http::get(
                "https://graph.facebook.com/v23.0/{$leadId}",
                [
                    'access_token' => $pageToken
                ]
            );

            if (!$response->successful()) {

                Log::error('Facebook Graph API Error', [
                    'lead_id' => $leadId,
                    'response' => $response->json()
                ]);

                return response('EVENT_RECEIVED', 200);
            }

            $leadData = $response->json();
            $leadData['form_id'] = $formId;
            $leadData['page_id'] = $pageId;

            //Log::info('FB LEAD RESPONSE', $leadData);

            $this->saveLead($leadData);
        } catch (\Throwable $e) {

            Log::error('FB WEBHOOK ERROR', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }

        return response('EVENT_RECEIVED', 200);
    }

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

        $pages = SocialMediaSetup::all();
        $forms = FacebookForm::all();

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
        $pageToken = SocialMediaSetup::where('page_id', $pageId)->value('access_token');
        $response = Http::get(
            "https://graph.facebook.com/v23.0/{$pageId}/leadgen_forms",
            [
                'fields' => 'id,name,status,created_time',
                'access_token' => $pageToken
            ]
        );
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

    private function saveLead($leadData)
    {
        $fields = collect($leadData['field_data'] ?? [])
            ->mapWithKeys(fn($item) => [
                $item['name'] => $item['values'][0] ?? null
            ]);

        try {
            $formID = $leadData['form_id'] ?? null;
            $form = FacebookForm::where(
                'facebook_form_id',
                $formID
            )->first();

            $assain_user = null;

            if ($formID) {
                $assain_user = UserWiseForm::where(
                    'form_id',
                    $form->id
                )->value('team_id');
            }

            $fullName = trim($fields['full_name'] ?? '');
            if ($fullName === '') {
                $firstName = 'Unknown';
                $lastName = 'Unknown';
            } else {
                $nameParts = explode(' ', $fullName, 2);

                if (count($nameParts) > 1) {
                    $firstName = $nameParts[0];
                    $lastName  = $nameParts[1];
                } else {
                    $firstName = $fullName;
                    $lastName  = $fullName;
                }
            }
            
            $email = $fields['email'] ?? null;
            $phone = $fields['phone'] ?? null;
            if (!$email && !$phone) {
                Log::warning("Facebook Lead missing both email and phone. Form ID: {$formID}");
                return;
            }
            $student = null;
            if ($form?->id && ($email || $phone)) {
                $student = Student::where('form_id', $form->id)
                    ->where(function ($q) use ($email, $phone) {
                        if ($email) {
                            $q->where('email', $email);
                        }

                        if ($phone) {
                            $q->orWhere('phone', $phone);
                        }
                    })
                    ->first();
            }

            $studentData = [
                'fname'          => $firstName,
                'lname'          => $lastName,
                'dateofbirth'    => $fields['date_of_birth'] ?? '1900-01-01',
                'gender'         => 0,
                'email'         => $email,
                'phone'         => $phone,
                'descountry_id'  => 19,
                'source_id'      => 9,
                'assain_user'    => $assain_user,
                'user_id' => 1,
                'form_id' => $form?->id,
            ];
            //LOg::info($studentData);
            if ($student) {
                if ($email) $studentData['email'] = $email;
                if ($phone) $studentData['phone'] = $phone;

                $student->update($studentData);
            } else {
                $studentData['email'] = $email;
                $studentData['phone'] = $phone;

                Student::create($studentData);
                //LOg::info("New student created from Facebook lead. Email: {$email}, Phone: {$phone}");
            }
        } catch (\Exception $e) {

            Log::error("Failed to save Facebook lead: " . $e->getMessage(), [
                'lead_data' => $leadData,
                'trace'     => $e->getTraceAsString()
            ]);
        }
    }
}
