<?php

namespace App\Models\Product;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnglishTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'listening',
        'reading',
        'writing',
        'speaking',
        'overall',
        'user_id',
    ];


    /**
     * Get the product that owns the EnglishTest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    /**
     * Get the user that owns the ProductFeesHd
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
