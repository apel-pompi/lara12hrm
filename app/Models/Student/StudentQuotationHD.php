<?php

namespace App\Models\Student;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentQuotationHD extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_no',
        'student_id',
        'sumamount',
        'notes',
        'status',
        'adddate',
        'user_id',
        'active'
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
     * Get the deatils that owns the StudentQuotationHD
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deatils(): HasMany
    {
        return $this->hasMany(StudentQuotation::class, 'quotation_hd_id');
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

    public static function getFees($quoat_id,$product_id){
        $data = StudentQuoationFee::with('fee')->where('quotation_hd_id',$quoat_id)->where('product_id',$product_id)->get();
        return $data;
    }
}
