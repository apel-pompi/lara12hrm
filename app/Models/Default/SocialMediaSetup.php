<?php

namespace App\Models\Default;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialMediaSetup extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'access_token',
        'verify_token',
    ];
}
