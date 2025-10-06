<?php

namespace App\Models\Product;

use App\Models\AgencySetting\Academic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'degree_id',
        'scoretype',
        'score',
        'user_id'
    ];

    /**
     * Get the dgeree that owns the ProductRequirement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function degree(): BelongsTo
    {
        return $this->belongsTo(Academic::class, 'degree_id');
    }
}
