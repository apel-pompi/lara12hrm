<?php

namespace App\Models\Student;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentQuotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_hd_id',
        'service_id',
        'user_id'
    ];

    /**
     * Get the student that owns the StudentQuotation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the service that owns the StudentQuotation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(StudentInService::class, 'service_id');
    }
    /**
     * Get the user that owns the StudentQuotation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
