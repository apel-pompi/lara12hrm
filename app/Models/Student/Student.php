<?php

namespace App\Models\Student;

use App\Models\Default\Country;
use App\Models\Student\StudentSource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'fname',
        'lname',
        'dateofbirth',
        'gender',
        'email',
        'phone',
        'contactpre',
        'ename',
        'ephone',
        'preaddcountry',
        'preaddstate',
        'preaddcity',
        'paddress',

        'pascountry',
        'pasnocountry',
        'passportno',
        'visatype',
        'visaexdate',
        'pvisades',

        'intakedate',
        'descountry_id',
        'stage_id',
        'metting_note',

        'photo',
        'assain_user',
        'source_id',
        'user_id',
        'status',
    ];

    /**
     * Get the user that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the assainuser that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assainuser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assain_user');
    }

    /**
     * Get the source that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(StudentSource::class, 'source_id');
    }

    /**
     * Get the stage that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(StudentStage::class, 'stage_id');
    }
    /**
     * Get the country that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'descountry_id');
    }

    /**
     * Get the service that owns the StudentInvoiceHD
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function service(): HasMany
    {
        return $this->hasMany(StudentInService::class, 'student_id', 'id');
    }
}
