<?php

namespace App\Models\Default;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class TransactionName extends Model
{
   use HasFactory;
   protected $fillable = [
        'name',
        'code',
        'adddate',
        'user_id',
        'active',
    ];

    /**
     * Get the user that owns the TransactionName
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
