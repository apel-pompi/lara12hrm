<?php

namespace App\Models\Product;

use App\Models\AgencySetting\Fees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFeesDt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'fees_hd_id',
        'fees_id',
        'amount',
        'insqty',
        'pay_type',
        'totalamount',
    ];

    /**
     * Get the feesHd that owns the ProductFeesDt
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feesHd(): BelongsTo
    {
        return $this->belongsTo(ProductFeesHd::class, 'fees_hd_id', 'id');
    }

    /**
     * Get the fees that owns the ProductFeesDt
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fees(): BelongsTo
    {
        return $this->belongsTo(Fees::class, 'fees_id');
    }
}
