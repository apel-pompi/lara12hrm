<?php

namespace App\Models\Student;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentQuotationHD extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quotation_no',
        'student_id',
        'product_id',
        'totalamount',
        'notes',
        'status',
        'adddate',
        'user_id',
        'active',
    ];

    /**
     * Get the student that owns the StudentQuotationHD
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
   

    /**
     * Get the quatFees that owns the StudentQuotationHD
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quatFees(): HasMany
    {
        return $this->hasMany(StudentQuoationFee::class, 'quotation_hd_id');
    }
    /**
     * Get the user that owns the StudentQuotationHD
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getFees($quoat_id){
        $data = StudentQuoationFee::with('fee','productfee')->where('quotation_hd_id',$quoat_id)->get();
        return $data;
    }
    
}
