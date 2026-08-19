<?php

namespace App\Services\SocialMedia;

use App\Models\SocialMedia\FacebookForm;
use App\Models\SocialMedia\SocialMediaContact;
use App\Models\SocialMedia\SocialMediaSetup;
use App\Models\SocialMedia\UserWiseForm;
use App\Models\Student\Student;
use App\Services\SocialMedia\ApiService\FacebookApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadService
{
    public function __construct(
        protected FacebookApiService $facebookApi
    ) {}

    /**
     * Create / Update Student from Facebook Lead
     */
    public function save(array $leadData): ?Student
    {

        DB::beginTransaction();

        try {
            /*
             * |--------------------------------------------------------------------------
             * | Lead Fields
             * |--------------------------------------------------------------------------
             */

            $fields = collect($leadData['field_data'] ?? [])
                ->mapWithKeys(function ($item) {
                    return [
                        $item['name'] => $item['values'][0] ?? null
                    ];
                });

            /*
             * |--------------------------------------------------------------------------
             * | Form
             * |--------------------------------------------------------------------------
             */

            $facebookForm = FacebookForm::where(
                'facebook_form_id',
                $leadData['form_id'] ?? null
            )->first();

            if (!$facebookForm) {
                Log::warning('Facebook Form Not Found', [
                    'facebook_form_id' => $leadData['form_id']
                ]);

                DB::rollBack();

                return null;
            }

            /*
             * |--------------------------------------------------------------------------
             * | Assign User
             * |--------------------------------------------------------------------------
             */

            $assignUser = UserWiseForm::where(
                'form_id',
                $facebookForm->id
            )->value('team_id');

            /*
             * |--------------------------------------------------------------------------
             * | Name
             * |--------------------------------------------------------------------------
             */

            $fullName = trim($fields['full_name'] ?? '');

            if ($fullName == '') {
                $firstName = 'Unknown';

                $lastName = 'Unknown';
            } else {
                $parts = explode(' ', $fullName, 2);

                if (count($parts) > 1) {
                    $firstName = $parts[0];

                    $lastName = $parts[1];
                } else {
                    $firstName = $fullName;

                    $lastName = $fullName;
                }
            }

            /*
             * |--------------------------------------------------------------------------
             * | Contact
             * |--------------------------------------------------------------------------
             */

            $email = $this->getField($fields->toArray(), [
                'email'
            ]);

            $phone = $this->getField($fields->toArray(), [
                'phone',
                'phone_number',
                'mobile',
                'mobile_number',
            ]);

            if (!$email && !$phone) {
                DB::rollBack();

                return null;
            }

            /*
             * |--------------------------------------------------------------------------
             * | Existing Student
             * |--------------------------------------------------------------------------
             */

            $student = Student::where(
                'form_id',
                $facebookForm->id
            )
                ->where(function ($q) use ($email, $phone) {
                    if ($email) {
                        $q->where('email', $email);
                    }

                    if ($phone) {
                        $q->orWhere('phone', $phone);
                    }
                })
                ->first();

            /*
             * |--------------------------------------------------------------------------
             * | Student Data
             * |--------------------------------------------------------------------------
             */

            $studentData = [
                'fname' => $firstName,
                'lname' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'dateofbirth' => $fields['date_of_birth'] ?? '1900-01-01',
                'gender' => 0,
                'descountry_id' => 19,
                'source_id' => 9,
                'form_id' => $facebookForm->id,
                'inbox_url' => $fields['inbox_url'] ?? null,
                'assain_user' => $assignUser,
                'user_id' => 1,
            ];

            /*
             * |--------------------------------------------------------------------------
             * | Save Student
             * |--------------------------------------------------------------------------
             */

            if ($student) {
                $student->update($studentData);
            } else {
                $student = Student::create($studentData);
            }

            /*
             * |--------------------------------------------------------------------------
             * | Messenger PSID
             * |--------------------------------------------------------------------------
             */

            $psid = null;

            $inboxUrl = $fields['inbox_url'] ?? null;

            if (
                $inboxUrl &&
                preg_match('/latest\/(\d+)/', $inboxUrl, $match)
            ) {
                $psid = $match[1];
            }

            /*
             * |--------------------------------------------------------------------------
             * | Social Contact
             * |--------------------------------------------------------------------------
             */

            if ($psid) {
                SocialMediaContact::updateOrCreate(
                    [
                        'platform' => 'messenger',
                        'platform_user_id' => $psid,
                    ],
                    [
                        'student_id' => $student->id,
                        'page_id' => $leadData['page_id'] ?? null,
                        'social_name' => $fullName,
                        'phone_number' => $phone,
                        'status' => 1,
                    ]
                );
            }

            DB::commit();

            return $student;
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('LeadService Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return null;
        }
    }

    public function receiveLead(Request $request): ?Student
    {
        $leadId = data_get(
            $request->all(),
            'entry.0.changes.0.value.leadgen_id'
        );

        $pageId = data_get(
            $request->all(),
            'entry.0.changes.0.value.page_id'
        );

        $formId = data_get(
            $request->all(),
            'entry.0.changes.0.value.form_id'
        );

        /*
         * |--------------------------------------------------------------------------
         * | Get Access Token
         * |--------------------------------------------------------------------------
         */

        $token = SocialMediaSetup::where(
            'platform',
            'facebook'
        )
            ->where(
                'page_id',
                $pageId
            )
            ->value('access_token');

        if (!$token) {
            Log::warning('Facebook Token Not Found');

            return null;
        }

        /*
         * |--------------------------------------------------------------------------
         * | Download Lead
         * |--------------------------------------------------------------------------
         */

        $leadData = $this->facebookApi->getLead(
            $token,
            $leadId
        );
        $leadData['form_id'] = $formId;
        $leadData['page_id'] = $pageId;

        if (!$leadData) {
            return null;
        }

        return $this->save($leadData);
    }

    private function getField(array $fields, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (!empty($fields[$key])) {
                return $fields[$key];
            }
        }

        return null;
    }
}
