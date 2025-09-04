<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WDocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'docname',
        'adddate',
        'totaluse',
        'user_id',
        'active'
    ];

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
