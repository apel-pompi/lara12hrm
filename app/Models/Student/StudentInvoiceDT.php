<?php

namespace App\Models\Student;

use App\Models\AgencySetting\Fees;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentInvoiceDT extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'student_invoices_dt';

    protected $fillable = [
        'invoice_hd_id',
        'product_id',
        'fees_id',
        'amount'
    ];

    /**
     * Get the header that owns the header
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function header() {
        return $this->belongsTo(StudentInvoiceHD::class, 'invoice_hd_id');
    }

    /**
     * Get the product that owns the product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product() {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the fee that owns the Studentfee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fee() {
        return $this->belongsTo(Fees::class, 'fees_id');
    }
}
