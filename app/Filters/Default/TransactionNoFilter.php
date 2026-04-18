<?php
namespace App\Filters\Default;

use App\Filters\BaseFilter;
use App\Filters\Components\Default\TrnType;
use App\Filters\Components\Default\TrnCode;
use App\Filters\Components\Default\Active;
use App\Filters\Components\Default\Branch;
use App\Filters\Components\Default\Name;

class TransactionNoFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            Name::class,
            TrnCode::class,
            Active::class,
            Branch::class
        ];
    }
}