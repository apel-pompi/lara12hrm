<?php

namespace App\Models\Product;

use App\Models\Default\Fees;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ProductFeesDt extends Model
{
    use HasFactory;

    protected $fillable = [
        'fees_hd_id',
        'fees_id',
        'amount',
        'insqty',
        'totalamount',
        'notes',
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
