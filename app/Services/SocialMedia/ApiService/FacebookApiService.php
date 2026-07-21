<?php

namespace App\Services\SocialMedia\ApiService;

use App\Services\SocialMedia\ApiService\MetaApiService;
use Illuminate\Support\Facades\Log;

class FacebookApiService extends MetaApiService
{


    /*
|--------------------------------------------------------------------------
| Get Lead
|--------------------------------------------------------------------------
*/

    public function getLead(

        string $accessToken,

        string $leadId

    ): array {

        return $this->get(

            $accessToken,

            $leadId

        );
    }

    /*
|--------------------------------------------------------------------------
| Get Page
|--------------------------------------------------------------------------
*/

    public function getPage(

        string $accessToken,

        string $pageId

    ): array {

        return $this->get(

            $accessToken,

            $pageId

        );
    }

    /*
|--------------------------------------------------------------------------
| Get User
|--------------------------------------------------------------------------
*/

    public function getUser(

        string $accessToken,

        string $userId

    ): array {

        return $this->get(

            $accessToken,

            $userId

        );
    }

    /*
|--------------------------------------------------------------------------
| Get Pages
|--------------------------------------------------------------------------
*/

    public function getPages(

        string $accessToken

    ): array {

        return $this->get(

            $accessToken,

            'me/accounts'

        );
    }

    /*
|--------------------------------------------------------------------------
| Get Forms
|--------------------------------------------------------------------------
*/

    public function getForms(

        string $accessToken,

        string $pageId

    ): array {

        return $this->get(

            $accessToken,

            "{$pageId}/leadgen_forms"

        );
    }

    /*
|--------------------------------------------------------------------------
| Get Form
|--------------------------------------------------------------------------
*/

    public function getForm(

        string $accessToken,

        string $formId

    ): array {

        return $this->get(

            $accessToken,

            $formId

        );
    }

    /*
|--------------------------------------------------------------------------
| Get Business
|--------------------------------------------------------------------------
*/

    public function getBusiness(

        string $accessToken,

        string $businessId

    ): array {

        return $this->get(

            $accessToken,

            $businessId

        );
    }

    /*
|--------------------------------------------------------------------------
| Get Profile Picture
|--------------------------------------------------------------------------
*/

    public function getProfilePicture(

        string $accessToken,

        string $pageId

    ): array {

        return $this->get(

            $accessToken,

            "{$pageId}/picture",

            [

                'redirect' => false

            ]

        );
    }

    /*
|--------------------------------------------------------------------------
| Debug Token
|--------------------------------------------------------------------------
*/

    public function debugToken(

        string $appToken,

        string $inputToken

    ): array {

        return $this->get(

            $appToken,

            'debug_token',

            [

                'input_token' => $inputToken

            ]

        );
    }

    public function sendMessengerMessage(

        string $accessToken,

        string $pageId,

        string $recipientId,

        string $message

    ): array {
        //Log::info('STEP-2 Before Facebook API');
        $response = $this->post(

            $accessToken,

            "{$pageId}/messages",

            [

                'recipient' => [

                    'id' => $recipientId,

                ],

                'messaging_type' => 'RESPONSE',

                'message' => [

                    'text' => $message,

                ],

            ]

        );
        // Log::info('Response from Facebook API', [

        //     'response' => $response

        // ]);
        return $response;
    }
}
