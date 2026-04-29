<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Attendance;
use App\Models\HRM\DeviceConfig;
use Illuminate\Http\Request;
use MshadyDev\ZKTeco\ZKTeco;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class ZktecoController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        try {
            $this->authorize('device.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/hrm/zkteco', [
            // Pass any necessary data to the view here
        ]);
    }

    public function connect(Request $request)
    {
        try {
            $this->authorize('device.connect');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $request->validate([
                'ip' => 'required|ip',
                'port' => 'required|integer|min:1|max:65535'
            ]);

            $ip = $request->ip;
            $port = $request->port;


            $zk = new ZKTeco($ip, $port);
            $zk->connect();

            $deviceInfo = [
                'platform' => $zk->getPlatform(),
                'os_version' => $zk->getFirmwareVersion(),
                'attendance_count' => count($zk->getAttendance()),
                'connected' => true
            ];


            $zk->disconnect();


            DeviceConfig::updateOrCreate(
                ['id' => 1],
                [
                    'ip' => $ip,
                    'port' => $port,
                    'last_connected_at' => now(),
                    'is_active' => true
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Device connected successfully!',
                'device_info' => $deviceInfo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sync(Request $request)
    {
        try {
            $this->authorize('device.sync');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $request->validate([
                'date' => 'required|date'
            ]);

            $syncDate = Carbon::parse($request->date)->format('Y-m-d');

            $config = DeviceConfig::find(1);

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please connect to device first'
                ], 400);
            }

            $zk = new ZKTeco($config->ip, $config->port);
            $zk->connect();

            // Get all attendance
            $attendances = $zk->getAttendance();

            $newRecords = 0;
            $duplicateRecords = 0;

            foreach ($attendances as $record) {
                // Extract record time
                $recordTime = isset($record['timestamp']) ? $record['timestamp'] : (isset($record['time']) ? $record['time'] : (isset($record['datetime']) ? $record['datetime'] : null));

                if (!$recordTime) continue;

                $recordDate = Carbon::parse($recordTime)->format('Y-m-d');

                // Filter by selected date
                if ($recordDate !== $syncDate) continue;

                // Extract user ID
                $userId = isset($record['user_id']) ? $record['user_id'] : (isset($record['uid']) ? $record['uid'] : (isset($record['id']) ? $record['id'] : 'unknown'));

                // Check if record exists
                $exists = Attendance::where('user_id', $userId)
                    ->where('record_time', $recordTime)
                    ->exists();

                if (!$exists) {
                    Attendance::create([
                        'user_id' => (string)$userId,
                        'record_time' => $recordTime,
                        'device_ip' => $config->ip,
                        'state' => $record['status'] ?? $record['type'] ?? 0,
                        'punch_type' => $record['punch'] ?? 1
                    ]);
                    $newRecords++;
                } else {
                    $duplicateRecords++;
                }
            }

            $zk->disconnect();

            // Update config
            $config->update([
                'last_synced_at' => now(),
                'total_records_synced' => $config->total_records_synced + $newRecords
            ]);

            return response()->json([
                'success' => true,
                'message' => "Sync completed for {$syncDate}!",
                'data' => [
                    'new_records' => $newRecords,
                    'duplicate_records' => $duplicateRecords,
                    'total_records' => count($attendances),
                    'sync_date' => $syncDate,
                    'last_sync' => now()->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function syncRange(Request $request)
    {
        try {
            $this->authorize('device.syncRange');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            $config = DeviceConfig::find(1);

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please connect to device first'
                ], 400);
            }

            $zk = new ZKTeco($config->ip, $config->port);
            $zk->connect();

            $attendances = $zk->getAttendance();

            $newRecords = 0;
            $duplicateRecords = 0;
            $datesProcessed = [];

            foreach ($attendances as $record) {
                $recordTime = isset($record['timestamp']) ? $record['timestamp'] : (isset($record['time']) ? $record['time'] : null);

                if (!$recordTime) continue;

                $recordDate = Carbon::parse($recordTime);

                // Check if within date range
                if ($recordDate->between($startDate, $endDate)) {
                    $datesProcessed[$recordDate->format('Y-m-d')] = true;

                    $userId = isset($record['user_id']) ? $record['user_id'] : (isset($record['uid']) ? $record['uid'] : 'unknown');

                    $exists = Attendance::where('user_id', $userId)
                        ->where('record_time', $recordTime)
                        ->exists();

                    if (!$exists) {
                        Attendance::create([
                            'user_id' => (string)$userId,
                            'record_time' => $recordTime,
                            'device_ip' => $config->ip,
                            'state' => $record['status'] ?? 0
                        ]);
                        $newRecords++;
                    } else {
                        $duplicateRecords++;
                    }
                }
            }

            $zk->disconnect();

            $config->update([
                'last_synced_at' => now(),
                'total_records_synced' => $config->total_records_synced + $newRecords
            ]);

            return response()->json([
                'success' => true,
                'message' => "Range sync completed!",
                'data' => [
                    'new_records' => $newRecords,
                    'duplicate_records' => $duplicateRecords,
                    'total_records' => count($attendances),
                    'date_range' => "{$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}",
                    'dates_processed' => array_keys($datesProcessed),
                    'last_sync' => now()->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Range sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkStatus()
    {
        try {
            $config = DeviceConfig::find(1);

            if (!$config) {
                return response()->json([
                    'connected' => false,
                    'message' => 'No device configured'
                ]);
            }

            $zk = new ZKTeco($config->ip, $config->port);
            $zk->connect();

            return response()->json([
                'connected' => true,
                'ip' => $config->ip,
                'port' => $config->port,
                'last_connected' => $config->last_connected_at,
                'last_synced' => $config->last_synced_at,
                'total_records' => $config->total_records_synced,
                'device_info' => [
                    'name' => $zk->getPlatform(),
                    'serial' => $zk->getSerialNumber()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'connected' => false,
                'message' => 'Status check failed'
            ]);
        }
    }

    public function getStats()
    {
        $stats = [
            'total_records' => Attendance::count(),
            'today_records' => Attendance::whereDate('record_time', today())->count(),
            'unique_users' => Attendance::distinct('user_id')->count('user_id'),
            'last_7_days' => Attendance::where('record_time', '>=', now()->subDays(7))->count(),
            'last_30_days' => Attendance::where('record_time', '>=', now()->subDays(30))->count()
        ];

        return response()->json($stats);
    }
}
