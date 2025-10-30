<?php

namespace App\Models\Product;

use App\Models\AgencySetting\Installment;
use App\Models\Default\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFeesHd extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'product_id',
        'country_id',
        'ins_id',
        'user_id',
        'netamount',
    ];

    protected $appends = ['country_names'];
    /**
     * Get the product that owns the ProductFeesHd
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the feesDt that owns the ProductFeesHd
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(ProductFeesDt::class, 'fees_hd_id', 'id');
    }
    /**
     * Get the installment that owns the ProductFeesHd
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class, 'ins_id');
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

    

    public function getCountryNamesAttribute()
    {
        if (!$this->country_id) {
            return [];
        }

        $ids = explode(',', $this->country_id);

        return Country::whereIn('id', $ids)->pluck('name')->toArray();
    }
}
