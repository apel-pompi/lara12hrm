<?php

namespace App\Models\AgencySetting;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WDocumentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'docname',
        'adddate',
        'totaluse',
        'user_id',
        'active'
    ];

    /**
     * Get all of the comments for the MasterCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function docusage(): HasMany
    {
        return $this->hasMany(WDocumentCheck::class, 'doctype_id');
    }

    /**
     * Get the user that owns the Workflow
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
