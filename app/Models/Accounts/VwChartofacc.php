<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VwChartofacc extends Model
{
    use HasFactory;

    protected $table = 'vw_chartofaccs';
    public $timestamps = false;

    protected $fillable = [
        'accountcode',
        'ledger_name',
        'accounttype',
        'accountusage',
        'analyticalcode',
        'active',
        'groupone_code',
        'groupone_name',
        'grouptwo_code',
        'grouptwo_name',
        'groupthree_code',
        'groupthree_name'
    ];
}
