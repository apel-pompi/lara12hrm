<?php

namespace App\Filters\Accounts;

use App\Filters\BaseFilter;
use App\Filters\Components\Accounts\GroupOne;
use App\Filters\Components\Accounts\GroupTwo;
use App\Filters\Components\Accounts\GroupThree;
use App\Filters\Components\Accounts\Description;
use App\Filters\Components\Accounts\AccountType;
use App\Filters\Components\Accounts\AccountUsage;
use App\Filters\Components\Accounts\AnalyticalCode;

class ChartOfAccountsFillter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            GroupOne::class,
            GroupTwo::class,
            GroupThree::class,
            Description::class,
            AccountType::class,
            AccountUsage::class,
            AnalyticalCode::class,
        ];
    }
}
