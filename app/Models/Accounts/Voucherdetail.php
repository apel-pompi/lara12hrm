<?php

namespace App\Models\Accounts;

use App\Models\HRM\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucherdetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vouchernumber',
        'accountcode',
        'subacccode',
        'currency',
        'exchagerate',
        'primeamt',
        'baseamt',
        'branch_id',
        'notes',
        'user_id',
    ];
    
    /**
     * Get the voucherHeader that owns the Voucherdetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucherHeader(): BelongsTo
    {
        return $this->belongsTo(Voucherheader::class, 'vouchernumber', 'vouchernumber');
    }

    /**
     * Get the ChartOFAccount that owns the Voucherdetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ChartOFAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'accountcode', 'accountcode');
    }

    /**
     * Get the subacccode that owns the Voucherdetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subacccode(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'subacccode', 'subcode');
    }

    /**
     * Get the branch that owns the Voucherdetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user that owns the Voucherdetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
