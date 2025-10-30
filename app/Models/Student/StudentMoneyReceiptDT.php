<?php

namespace App\Models\Student;

use App\Models\AgencySetting\Fees;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentMoneyReceiptDT extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'insnumber_id',
        'mrnumber_id',
        'fees_id',
        'amount'
    ];

    /**
     * Get the fees that owns the StudentMoneyReceiptDT
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fees(): BelongsTo
    {
        return $this->belongsTo(Fees::class, 'fees_id');
    }

    
}
