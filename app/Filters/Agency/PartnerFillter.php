<?php

namespace App\Filters\Agency;

use App\Filters\BaseFilter;
use App\Filters\Components\Agency\CountryID;
use App\Filters\Components\Agency\PartnerType;
use App\Filters\Components\Agency\Workflow;
use App\Filters\Components\Default\Name;

class PartnerFillter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            Name::class,
            CountryID::class,
            Workflow::class,
            PartnerType::class
        ];
    }
}
