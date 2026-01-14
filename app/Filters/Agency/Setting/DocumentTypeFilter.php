<?php
namespace App\Filters\Agency\Setting;

use App\Filters\BaseFilter;
use App\Filters\Components\Agency\Setting\DocName;


class DocumentTypeFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            DocName::class,
        ];
    }
}