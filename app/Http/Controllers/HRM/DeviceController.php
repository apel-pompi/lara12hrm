<?php

namespace App\Http\Controllers\HRM;


use App\Http\Controllers\Controller;
use App\Models\HRM\Attendance;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeviceController extends Controller
{

    public function device()
    {
        return Inertia::render('allpages/hrm/device');
    }

    public function syncData(Request $request)
    {
        $request->validate([
            'senddate' => 'required|date_format:Y-m-d',
        ]);

        return response()->json([
            'success' => true,
            'date' => $request->senddate,
            'message' => 'Date received successfully'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'record_time' => 'required',
            'device_ip' => 'required',
            'state' => 'nullable|integer',
        ]);

        try {
            $exists = Attendance::where('user_id', $request->user_id)
                ->where('record_time', $request->record_time)
                ->where('device_ip', $request->device_ip)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate attendance — already exists for this user and time.'
                ], 200);
            }
            $attendance = Attendance::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully!',
                'data' => $attendance
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
