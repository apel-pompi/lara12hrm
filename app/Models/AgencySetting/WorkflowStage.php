<?php

namespace App\Models\AgencySetting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkflowStage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workflow_id',
        'stagename',
        'stage',
    ];


    /**
     * Get the documenttype that owns the WDocumentCheck
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documentChecks(): HasMany
    {
        return $this->hasMany(WDocumentCheck::class, 'workstage_id');
    }
}
