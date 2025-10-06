<?php

namespace App\Models\Product;

use App\Models\Partner\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'partner_id',
        'product_type_id',
        'revinue_type',
        'duration',
        'intak_month',
        'description',
        'note',
        'user_id',
        'active',
    ];

    /**
     * Get the partner that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    /**
     * Get the productype that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productype(): BelongsTo
    {
        return $this->belongsTo(ProductTypeSetup::class, 'product_type_id');
    }

    
}
