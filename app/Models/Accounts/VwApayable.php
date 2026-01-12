<?php

namespace App\Models\Accounts;

use App\Models\HRM\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VwApayable extends Model
{
    use HasFactory;
    protected $table = 'vw_apayable';
    public $timestamps = false;

    protected $fillable = [
        'suppliercode',
        'suppliername',
        'branch_id',
        'accountcode',
        'description',
        'contact_person',
        'payableamt'
    ];

     /**
     * Get the ChartOFAccount that owns the VwUnPaidInv
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ChartOFAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'accountcode', 'accountcode');
    }
    
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
