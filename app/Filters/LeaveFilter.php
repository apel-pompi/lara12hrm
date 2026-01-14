<?php
namespace App\Filters;

use App\Filters\BaseFilter;
use App\Filters\Components\Default\FormDate;
use App\Filters\Components\Default\ToDate;
use App\Filters\Components\PersonalInfo\EmployeeLeave;
use App\Filters\Components\PersonalInfo\LeaveName;
use App\Filters\Components\PersonalInfo\SubstituteLeave;

class LeaveFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            LeaveName::class,
            EmployeeLeave::class,
            FormDate::class,
            ToDate::class,
            SubstituteLeave::class
        ];
    }
}