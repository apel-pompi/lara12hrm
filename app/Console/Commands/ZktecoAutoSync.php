<?php

namespace App\Console\Commands;

use App\Events\HRM\AttendanceSyncCompleted;
use App\Models\HRM\DeviceConfig;
use Illuminate\Console\Command;
use MshadyDev\ZKTeco\ZKTeco;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ZktecoAutoSync extends Command
{
    protected $signature = 'zkteco:auto-sync {date?}';
    protected $description = 'Automatically sync ZKTeco attendance for a date';

    public function handle()
    {
        $date = $this->argument('date') ?: Carbon::now()->format('Y-m-d');
        $syncDate = Carbon::parse($date)->format('Y-m-d');

        $config = DeviceConfig::find(1);

        if (!$config || !$config->is_active) {
            $this->warn('No active device configured. Skipping auto-sync.');
            return 1;
        }

        try {
            $zk = new ZKTeco($config->ip, $config->port);
            $zk->connect();

            $attendances = $zk->getAttendance();

            $newRecords = 0;
            $duplicateRecords = 0;

            foreach ($attendances as $record) {
                $recordTime = $record['timestamp'] ?? $record['time'] ?? $record['datetime'] ?? null;

                if (!$recordTime) continue;

                $recordDate = Carbon::parse($recordTime)->format('Y-m-d');

                if ($recordDate !== $syncDate) continue;

                $userId = $record['user_id'] ?? $record['uid'] ?? $record['id'] ?? 'unknown';

                $exists = \App\Models\HRM\Attendance::where('user_id', $userId)
                    ->where('record_time', $recordTime)
                    ->exists();

                if (!$exists) {
                    \App\Models\HRM\Attendance::create([
                        'user_id' => (string) $userId,
                        'record_time' => $recordTime,
                        'device_ip' => $config->ip,
                        'state' => $record['status'] ?? $record['type'] ?? 0,
                        'punch_type' => $record['punch'] ?? 1,
                    ]);
                    $newRecords++;
                } else {
                    $duplicateRecords++;
                }
            }

            $zk->disconnect();

            $config->update([
                'last_synced_at' => now(),
                'total_records_synced' => $config->total_records_synced + $newRecords,
            ]);

            $result = [
                'success' => true,
                'message' => "Auto-sync completed for {$syncDate}!",
                'data' => [
                    'new_records' => $newRecords,
                    'duplicate_records' => $duplicateRecords,
                    'total_records' => count($attendances),
                    'sync_date' => $syncDate,
                    'last_sync' => now()->format('Y-m-d H:i:s'),
                ],
            ];

            AttendanceSyncCompleted::dispatch($result, Auth::id());

            $this->info("Auto-sync completed: {$newRecords} new, {$duplicateRecords} duplicate.");
            return 0;
        } catch (\Exception $e) {
            Log::error('ZKTeco auto-sync failed: ' . $e->getMessage());
            $this->error('Auto-sync failed: ' . $e->getMessage());
            return 1;
        }
    }
}
