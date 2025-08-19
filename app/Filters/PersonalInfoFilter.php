<?php
namespace App\Filters;

use App\Filters\BaseFilter;
use App\Filters\Components\PersonalInfo\EmployeeeName;
use App\Filters\Components\PersonalInfo\EmployeeID;
use App\Filters\Components\Default\Branch;
use App\Filters\Components\Default\Department;
use App\Filters\Components\Default\Designation;
use App\Filters\Components\PersonalInfo\Blood;


class PersonalInfoFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            EmployeeID::class,
            EmployeeeName::class,
            Branch::class,
            Department::class,
            Designation::class,
            Blood::class
        ];
    }
}