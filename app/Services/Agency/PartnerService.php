<?php

namespace App\Services\Agency;

use App\Filters\Agency\PartnerFillter;
use App\Models\Partner\Partner;

class PartnerService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Partner::with(['partnertype','state.country','city'])->orderBy('id', 'DESC');
        $workhour = resolve(PartnerFillter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        return $workhour;
    }
}
