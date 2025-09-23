<?php
namespace App\Filters\Default;

use App\Filters\BaseFilter;
use App\Filters\Components\Default\TrnType;
use App\Filters\Components\Default\TrnCode;
use App\Filters\Components\Default\Active;
use App\Filters\Components\Default\Branch;


class TransactionNoFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            TrnType::class,
            TrnCode::class,
            Active::class,
            Branch::class
        ];
    }
}