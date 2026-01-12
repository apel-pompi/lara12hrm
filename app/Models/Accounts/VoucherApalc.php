<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoucherApalc extends Model
{
    use HasFactory;

    protected $fillable = [
        'vouchernumber',
        'invnumber',
        'voucherdate',
        'branch_id',
        'currency',
        'exchagerate',
        'primeamt',
        'baseamt',
        'user_id',
    ];
}
