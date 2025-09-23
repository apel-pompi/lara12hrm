<?php
namespace App\Filters\Agency;

use App\Filters\BaseFilter;
use App\Filters\Components\Agency\AssaignUser;
use App\Filters\Components\Agency\Country;
use App\Filters\Components\Default\Email;
use App\Filters\Components\Default\IDName;
use App\Filters\Components\Default\Phone;
use App\Filters\Components\Default\Status;

class StudentFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            IDName::class,
            Phone::class,
            Email::class,
            Country::class,
            AssaignUser::class,
            Status::class,
        ];
    }
}