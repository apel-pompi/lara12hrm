<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayDt extends Model
{
    use HasFactory;

    protected $fillable = [
        'holidate',
        'holitypes',
        'holihd_id',
    ];
}
