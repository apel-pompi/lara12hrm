<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HolidayDt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'holidate',
        'holitypes',
        'holihd_id',
    ];
}
