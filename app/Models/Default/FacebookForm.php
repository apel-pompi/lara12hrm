<?php

namespace App\Models\Default;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacebookForm extends Model
{
    use HasFactory;

    protected $table = 'facebook_forms';

    protected $fillable = [
        'facebook_form_id',
        'form_name',
        'status',
        'created_time',
        'page_id'
    ];
}
