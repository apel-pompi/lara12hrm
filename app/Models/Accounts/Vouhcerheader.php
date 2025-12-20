<?php

namespace App\Models\Accounts;

use App\Models\HRM\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vouhcerheader extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vouchernumber',
        'voucherdate',
        'referance',
        'yearname',
        'monthname',
        'branch_id',
        'status',
        'user_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
