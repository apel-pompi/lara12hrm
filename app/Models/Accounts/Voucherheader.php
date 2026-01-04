<?php

namespace App\Models\Accounts;

use App\Models\HRM\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucherheader extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vouchernumber',
        'voucherdate',
        'referance',
        'yearname',
        'monthname',
        'branch_id',
        'notes',
        'status',
        'user_id',
    ];

    /**
     * Get all of the voucherDetails for the Vouhcerheader
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voucherdt(): HasMany
    {
        return $this->hasMany(Voucherdetail::class, 'vouchernumber', 'vouchernumber');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
