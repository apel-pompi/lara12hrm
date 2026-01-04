<?php

namespace App\Models\Accounts;

use App\Models\HRM\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodesParam extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'type',
        'code',
        'accdisc',
        'cracc',
        'dracc',
        'props',
        'percent',
        'acctax',
        'branch_id',
        'user_id',
        'active',
    ];

    public function setDodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    /**
     * Get the craccount that owns the CodesParam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function craccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'cracc','accountcode');
    }

    /**
     * Get the draccount that owns the CodesParam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function draccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'dracc','accountcode');
    }

    /**
     * Get the acctax that owns the CodesParam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taxaccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'acctax','accountcode');
    }
    /**
     * Get the branch that owns the CodesParam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    /**
     * Get the user that owns the CodesParam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
