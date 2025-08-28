<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'empid',
        'empname',
        'joindate',
        'branch_id',
        'dept_id',
        'des_id',
        'dateofbirth',
        'gender',
        'present',
        'permanent',
        'phonepersonal',
        'phoneoffice',
        'email',
        'blood',
        'nidpass',
        'photo',
        'active',
        
    ];

    /**
     * Get the branch that owns the PersonalInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    
    /**
     * Get the designation that owns the PersonalInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'des_id');
    }

    /**
     * Get the department that owns the PersonalInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    /**
     * Get all of the leaves for the PersonalInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'empid', 'empid');
    }
}
