<?php

namespace App\Models\Student;

use App\Models\AgencySetting\WDocumentType;
use App\Models\AgencySetting\Workflow;
use App\Models\AgencySetting\WorkflowStage;
use App\Models\Partner\Partner;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'applcation_id',
        'workflow_id',
        'partner_id',
        'product_id',
        'stage_id',
        'doc_id',
        'docname',
        'user_id',
    ];

    /**
     * Get the application that owns the ApplicationDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(StudentApplication::class, 'applcation_id');
    }

    /**
     * Get the workflow that owns the ApplicationDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    /**
     * Get the partner that owns the ApplicationDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    /**
     * Get the product that owns the ApplicationDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the stage that owns the ApplicationDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(WorkflowStage::class, 'stage_id');
    }

    /**
     * Get the documentid that owns the ApplicationDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function documentid(): BelongsTo
    {
        return $this->belongsTo(WDocumentType::class, 'doc_id');
    }
    /**
     * Get the user that owns the ApplicationDocument
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
