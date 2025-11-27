<?php

namespace App\Models\Student;

use App\Models\AgencySetting\Fees;
use App\Models\Product\Product;
use App\Models\Product\ProductFeesDt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentQuoationFee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quotation_hd_id',
        'fee_id',
        'amount',
        'user_id',
    ];

    /**
     * Get the product that owns the StudentQuoationFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the productfee that owns the StudentQuoationFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productfee(): BelongsTo
    {
        return $this->belongsTo(ProductFeesDt::class, 'fee_id', 'fees_id');
    }

    /**
     * Get the studentService that owns the StudentQuoationFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function studentService(): BelongsTo
    {
        return $this->belongsTo(StudentInService::class, 'student_id', 'student_id');
    }

    /**
     * Get the fee that owns the StudentQuoationFee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fees::class, 'fee_id','id');
    }
}
