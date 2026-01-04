<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VwPayInvc extends Model
{
    use HasFactory;

    protected $table = 'vw_payinvc';
    public $timestamps = false;
}
