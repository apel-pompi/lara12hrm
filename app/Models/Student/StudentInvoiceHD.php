<?php

namespace App\Models\Student;

use App\Models\Accounts\Voucherdetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentInvoiceHD extends Model
{
    use HasFactory, SoftDeletes;
    
    
    protected $table = 'student_invoice_hd';

    protected $fillable = [
        'insnumber',
        'insdate',
        'student_id',
        'payterms',
        'accountcode',
        'chequeno',
        'bankname',
        'bankbranch',
        'transno',
        'currency',
        'exchrate',
        'note',
        'shortnote',
        'totalamt',
        'disc_rate',
        'disc_amt',
        'netamount',
        'sign',
        'status',
        'refe_code',
        'user_id'
    ];

    /**
     * Get the student that owns the student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function student() {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the user that owns the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the details that owns the details
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details() {
        return $this->hasMany(StudentInvoiceDT::class, 'invoice_hd_id');
    }

    /**
     * Get the mrdetails that owns the StudentInvoiceHD
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mrdetails(): HasMany
    {
        return $this->hasMany(StudentMoneyReceiptDT::class, 'mrnumber_id','id');
    }

    /**
     * Get the voucherDetails that owns the StudentInvoiceHD
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voucherDetails(): HasMany
    {
        return $this->hasMany(Voucherdetail::class, 'vouchernumber','insnumber');
    }
}
