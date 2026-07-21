<?php

namespace App\Models\SocialMedia;

use App\Models\SocialMedia\FacebookForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWiseForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'team_id',
        'counsilor_id',
        'status',
    ];

    protected $casts = [
        'counsilor_id' => 'array',
    ];


    public function form()
    {
        return $this->belongsTo(FacebookForm::class, 'form_id');
    }

    public function teamLeader()
    {
        return $this->belongsTo(User::class, 'team_id');
    }
}
