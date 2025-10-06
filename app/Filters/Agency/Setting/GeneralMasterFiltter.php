<?php
namespace App\Filters\Agency\Setting;

use App\Filters\BaseFilter;
use App\Filters\Components\Agency\Setting\CatName;

class GeneralMasterFiltter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            CatName::class,
        ];
    }
}