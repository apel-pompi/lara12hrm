<?php

namespace App\Models\Default;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppFormSetup extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_form_setups';

    protected $fillable = [
        'phone_number',
        'phone_id',
        'waba_id',
        'team_id',
        'counsilor_id',
        'status',
    ];

    protected $casts = [
        'counsilor_id' => 'array',
    ];

    public function number()
    {
        return $this->belongsTo(WhatsAppsNumber::class, 'phone_id', 'phone_id');
    }

    public function teamLeader()
    {
        return $this->belongsTo(User::class, 'team_id');
    }
}
