<?php

namespace App\Models\AgencySetting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class WDocumentCheck extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workflow_id',
        'doctype_id',
        'workstage_id',
        'user_id',
        'active'
    ];

    /**
     * Get the documenttype that owns the WDocumentCheck
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function documenttype(): BelongsTo
    {
        return $this->belongsTo(WDocumentType::class, 'doctype_id');
    }
    

}
