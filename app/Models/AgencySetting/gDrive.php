<?php

namespace App\Models\AgencySetting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Http;

class gDrive extends Model
{

    use HasFactory;

    protected $fillable = [
        'student_id',
        'applcation_id',
        'stage_id',
        'docname',
        'folder_id',
        'file_id',
        'file_url',
        'user_id'
    ];

    public static function token()
    {
        $client_id = \Config('services.google.client_id');
        $client_secret = \Config('services.google.client_secret');
        $refresh_token = \Config('services.google.refresh_token');

        $response = Http::post('https://oauth2.googleapis.com/token', [

            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',

        ]);
        $accessToken = json_decode((string) $response->getBody(), true)['access_token'];

        return $accessToken;
    }

    public static function createFolder($name, $parentId = null)
    {
        $accessToken = self::token();
        if (!$accessToken) return null;

        $metadata = [
            'name'     => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
        ];

        if ($parentId) {
            $metadata['parents'] = [$parentId];
        }

        $response = Http::withToken($accessToken)
            ->post('https://www.googleapis.com/drive/v3/files', $metadata);

        if ($response->failed()) {
            return null;
        }

        return $response->json()['id'] ?? null;
    }

    public static function listFolders($parentId = null)
    {
        $accessToken = self::token();
        if (!$accessToken) return [];

        $query = "mimeType = 'application/vnd.google-apps.folder' and trashed = false";
        if ($parentId) {
            $query .= " and '$parentId' in parents";
        }

        $response = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/drive/v3/files', [
                'q' => $query,
                'fields' => 'files(id, name, parents)'
            ]);

        if ($response->failed()) {
            return [];
        }

        return $response->json()['files'] ?? [];
    }
}
