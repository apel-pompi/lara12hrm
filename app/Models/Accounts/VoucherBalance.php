<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherBalance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vouchernumber',
        'accountcode',
        'subacccode',
        'voucherdate',
        'branch_id',
        'referance',
        'yearname',
        'monthname',
        'currency',
        'exchagerate',
        'primeamt',
        'baseamt',
        'status',
        'user_id',
    ];
}
