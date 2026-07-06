<?php

namespace App\Models\Default;

use Illuminate\Database\Eloquent\Model;

class WhatsAppsNumber extends Model
{
    protected $fillable = [
        'waba_id',
        'phone_id',
        'phoneno',
        'verified_name',
        'status',
    ];
}
