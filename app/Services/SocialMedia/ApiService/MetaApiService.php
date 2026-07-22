<?php

namespace App\Services\SocialMedia\ApiService;

use App\Models\SocialMedia\SocialMediaSetup;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class MetaApiService
{

    /**
     * Graph Version
     */
    protected string $version = 'v23.0';

    /**
     * Base URL
     */
    protected string $baseUrl = 'https://graph.facebook.com';

    protected function getSetup(string $platform): SocialMediaSetup
    {
        return SocialMediaSetup::where(

            'platform',

            $platform

        )->firstOrFail();
    }

    /**
     * GET Request
     */
    protected function get(

        string $accessToken,

        string $endpoint,

        array $query = []

    ): array {

        try {

            $response = Http::acceptJson()

                ->withToken($accessToken)

                ->get(

                    "{$this->baseUrl}/{$this->version}/{$endpoint}",

                    $query

                );

            return $this->response($response);
        } catch (\Throwable $e) {

            Log::error('META GET', [

                'endpoint' => $endpoint,

                'message' => $e->getMessage(),

            ]);

            throw $e;
        }
    }

    /**
     * POST Request
     */
    protected function post(

        string $accessToken,

        string $endpoint,

        array $payload = []

    ): array {
        // Log::info('META SEND REQUEST', [

        //     'endpoint' => "{$this->baseUrl}/{$this->version}/{$endpoint}",

        //     'payload' => $payload,

        // ]);
        try {

            $response = Http::acceptJson()

                ->withToken($accessToken)

                ->post(

                    "{$this->baseUrl}/{$this->version}/{$endpoint}",

                    $payload

                );

            // Log::info('META RESPONSE', [

            //     'status' => $response->status(),

            //     'body' => $response->json(),

            // ]);
            return $this->response($response);
        } catch (\Throwable $e) {

            Log::error('META POST', [

                'endpoint' => $endpoint,

                'message' => $e->getMessage(),

            ]);

            throw $e;
        }
    }

    /**
     * UPLOAD Request (Multipart)
     */
    protected function upload(
        string $accessToken,
        string $endpoint,
        string $filePath,
        string $fileParam = 'file',
        array $payload = []
    ): array {
        try {
            $request = Http::acceptJson()
                ->withToken($accessToken)
                ->attach(
                    $fileParam,
                    fopen($filePath, 'r'),
                    basename($filePath)
                );
            
            // For Messenger, we might need to send nested JSON as strings in multipart
            $formattedPayload = [];
            foreach ($payload as $key => $value) {
                if (is_array($value)) {
                    $formattedPayload[$key] = json_encode($value);
                } else {
                    $formattedPayload[$key] = (string) $value;
                }
            }

            $response = $request->post(
                "{$this->baseUrl}/{$this->version}/{$endpoint}",
                $formattedPayload
            );

            return $this->response($response);
        } catch (\Throwable $e) {
            Log::error('META UPLOAD', [
                'endpoint' => $endpoint,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * DELETE Request
     */
    protected function delete(

        string $accessToken,

        string $endpoint

    ): array {

        $response = Http::acceptJson()

            ->withToken($accessToken)

            ->delete(

                "{$this->baseUrl}/{$this->version}/{$endpoint}"

            );

        return $this->response($response);
    }

    /**
     * PUT Request
     */
    protected function put(

        string $accessToken,

        string $endpoint,

        array $payload = []

    ): array {

        $response = Http::acceptJson()

            ->withToken($accessToken)

            ->put(

                "{$this->baseUrl}/{$this->version}/{$endpoint}",

                $payload

            );

        return $this->response($response);
    }

    /**
     * Parse Response
     */
    protected function response(

        Response $response

    ): array {

        if ($response->successful()) {

            return $response->json();
        }

        Log::error(

            'META API ERROR',

            [

                'status' => $response->status(),

                'body' => $response->json(),

            ]

        );

        throw new \Exception(

            $response->json()['error']['message']

                ?? 'Meta API Error'

        );
    }
}
