<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowStage extends Model
{
    use HasFactory;

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
