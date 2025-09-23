<?php

namespace App\Models\Product;

use App\Models\Partner\Partner;
use App\Models\Partner\PartnerBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'partner_id',
        'partner_branch_id',
        'product_type_id',
        'revinue_type',
        'duration',
        'intak_month',
        'description',
        'note',
        'user_id',
        'active',
    ];

    protected $appends = ['branch_name'];
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

    public function getBranchNameAttribute()
    {
        if (!$this->partner_branch_id) {
            return [];
        }

        $ids = explode(',', $this->partner_branch_id);

        return PartnerBranch::whereIn('id', $ids)->pluck('branch_name')->toArray();
    }
}
