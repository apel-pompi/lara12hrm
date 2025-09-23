<?php
namespace App\Filters\Agency\Setting;

use App\Filters\BaseFilter;
use App\Filters\Components\Default\Name;

class InstallmentFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            Name::class,
        ];
    }
}