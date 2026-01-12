<?php

namespace App\Models\Accounts;

use App\Models\HRM\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * Get the voucherHeader that owns the Voucherdetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucherHeader(): BelongsTo
    {
        return $this->belongsTo(Voucherheader::class, 'vouchernumber', 'vouchernumber');
    }

     /**
     * Get the branch that owns the Voucherdetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
