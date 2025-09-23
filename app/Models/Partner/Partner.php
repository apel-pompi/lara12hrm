<?php

namespace App\Models\Partner;

use App\Models\City;
use App\Models\Partner\PartnerTypeSetup;
use App\Models\State;
use App\Models\User;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'workflow_id',
        'master_cat_id',
        'partner_type_id',
        'state_id',
        'city_id',
        'brn',
        'currency',
        'phone',
        'email',
        'fax',
        'website',
        'photo',
        'partner_branch_id',
        'user_id',
        'active',
    ];

    protected $appends = ['workflow_names'];

    /**
     * Get the user that owns the MasterCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getWorkflowNamesAttribute()
    {
        if (!$this->workflow_id) {
            return [];
        }

        $ids = explode(',', $this->workflow_id);

        return Workflow::whereIn('id', $ids)->pluck('name')->toArray();
    }

    /**
     * Get the partnertype that owns the Partner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partnertype(): BelongsTo
    {
        return $this->belongsTo(PartnerTypeSetup::class, 'partner_type_id');
    }

    /**
     * Get all of the state for the Partner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class,'state_id');
    }

    /**
     * Get all of the state for the Partner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class,'city_id');
    }
}
