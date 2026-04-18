<?php

namespace App\Services;

use App\Models\HRM\Attendance;
use MshadyDev\ZKTeco\ZKTeco;
use Carbon\Carbon;

class ZKTecoService
{
    protected $device;
    protected $ip;
    protected $port;

    public function __construct()
    {
        $this->ip = env('ZKTECO_IP', '118.67.221.58');
        $this->port = env('ZKTECO_PORT', 4370);
    }

    public function syncAttendance()
    {
        try {
            
            $this->device = new ZKTeco($this->ip, $this->port);
            $this->device->connect();
            
            
            $attendances = $this->device->getAttendance();
            
            $syncedCount = 0;
            
            foreach ($attendances as $record) {
                
                $exists = Attendance::where('user_id', $record['user_id'])
                    ->where('record_time', $record['timestamp'])
                    ->exists();
                
                if (!$exists) {
                    Attendance::create([
                        'user_id' => $record['user_id'],
                        'record_time' => $record['timestamp'],
                        'device_ip' => $this->ip,
                        'state' => $record['status'] ?? 0,
                    ]);
                    $syncedCount++;
                }
            }
            
            
            $this->device->disconnect();
            
            return [
                'success' => true,
                'message' => "{$syncedCount} records synced successfully",
                'count' => $syncedCount
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error ' . $e->getMessage()
            ];
        }
    }
}