<?php
namespace App\Filters\Default;

use App\Filters\BaseFilter;
use App\Filters\Components\Default\Active;
use App\Filters\Components\Default\Name;
use App\Filters\Components\Default\AddDate;



class TransactionNameFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            Active::class,
            Name::class,
            AddDate::class
        ];
    }
}