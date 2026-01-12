<?php

namespace App\Models\Accounts;

use App\Models\HRM\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VwPayInvc extends Model
{
    use HasFactory;

    protected $table = 'vw_payinvc';
    public $timestamps = false;

    protected $fillable = [
        'suppliercode',
        'invicenumber',
        'date',
        'branch_id',
        'currency',
        'exchagerate',
        'primeamt',
        'amount'
    ];

    /**
     * Get the branch that owns the VwUnPaidInv
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
