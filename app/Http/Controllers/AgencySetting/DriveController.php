<?php

namespace App\Http\Controllers\AgencySetting;

use App\Http\Controllers\Controller;
use App\Models\AgencySetting\gDrive;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DriveController extends Controller
{
    public function listDriveFolders(Request $request)
    {
        $parentId = $request->get('parent_id'); // optional
        $folders = gDrive::listFolders($parentId);

        return response()->json($folders);
    }

    public function uploadFile(Request $request)
    {
        
        $request->validate([
            'student_id' => 'required|integer',
            'applcation_id' => 'required|integer',
            'check_id' => 'required|integer',
            'docname' => 'required|string',
            'photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240', // 10MB limit
            'folder_id' => 'required|string',
        ]);

        $file = $request->file('photo');
        $folderId = $request->folder_id;
        $name = time() . '_' . $file->getClientOriginalName();
        $path = $file->getRealPath();

        $client = new Client();

        // Google Drive Access Token
        $accessToken = gDrive::token();

        // Multipart request to upload file
        $multipart = [
            [
                'name' => 'metadata',
                'contents' => json_encode([
                    'name' => $name,
                    'parents' => [$folderId],
                ]),
                'headers' => ['Content-Type' => 'application/json; charset=UTF-8'],
            ],
            [
                'name' => 'file',
                'contents' => fopen($file->getRealPath(), 'r'),
                'filename' => $name,
            ],
        ];

        $response = $client->request('POST', 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart', [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
            ],
            'multipart' => $multipart,
        ]);

        $result = json_decode($response->getBody(), true);
        $fileId = $result['id'];
        // Optional: make file publicly viewable
        $client->post("https://www.googleapis.com/drive/v3/files/{$fileId}/permissions", [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'role' => 'reader',
                'type' => 'anyone',
            ],
        ]);
        $fileUrl = "https://drive.google.com/uc?id={$fileId}";
        // $stageExists = \DB::table('workflow_stages')->where('id', $request->stage_id)->exists();
        // if (!$stageExists) {
        //     return response()->json(['error' => 'Stage does not exist'], 422);
        // }
        gDrive::create([
            'student_id' => $request->student_id,
            'applcation_id' => $request->applcation_id,
            'stage_id' => $request->check_id,
            'docname' => $request->docname,
            'folder_id' => $folderId,
            'file_id' => $fileId,
            'file_url' => $fileUrl,
            'user_id' => auth()->id(),
        ]);

        
    }
}
