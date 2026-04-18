<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'ip',
        'port',
        'last_connected_at',
        'last_synced_at',
        'total_records_synced',
        'is_active',
    ];
}
