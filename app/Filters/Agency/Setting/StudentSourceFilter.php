<?php
namespace App\Filters\Agency\Setting;

use App\Filters\BaseFilter;
use App\Filters\Components\Default\Name;

class StudentSourceFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            Name::class,
        ];
    }
}