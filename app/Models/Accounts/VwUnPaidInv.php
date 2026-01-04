<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VwUnPaidInv extends Model
{
     use HasFactory;

    protected $table = 'vw_unpaidinv';
    public $timestamps = false;
}
