<?php

namespace App\Models\Student;

use App\Models\AgencySetting\Workflow;
use App\Models\Partner\PartnerBranch;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'workflow_id',
        'partner_branch_id',
        'product_id',
        'stage',
        'status',
        'saleprice',
        'user_id'
    ];

    /**
     * Get the student that owns the StudentApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    /**
     * Get the workflow that owns the StudentActivities
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }


    /**
     * Get the partnerBranch that owns the StudentActivities
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partnerBranch(): BelongsTo
    {
        return $this->belongsTo(PartnerBranch::class, 'partner_branch_id');
    }


    /**
     * Get the product that owns the StudentActivities
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the user that owns the StudentApplication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
