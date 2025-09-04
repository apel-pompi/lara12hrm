<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTypeSetup extends Model
{
    use HasFactory;

    protected $fillable = [
        'producttypename',
        'mastercaterory_id',
        'user_id',
        'active'
    ];

    /**
     * Get the user that owns the MasterCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the mastercategory that owns the PartnerTypeSetup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mastercategory(): BelongsTo
    {
        return $this->belongsTo(MasterCategory::class, 'mastercaterory_id');
    }
}
