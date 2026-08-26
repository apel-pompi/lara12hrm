<?php

namespace App\Filters\Agency;

use App\Filters\BaseFilter;
use App\Filters\Components\Agency\AssaignUser;
use App\Filters\Components\Agency\Country;
use App\Filters\Components\Agency\Source;
use App\Filters\Components\Agency\StudentID;
use App\Filters\Components\Default\CreateAt;
use App\Filters\Components\Default\Email;
use App\Filters\Components\Default\IDName;
use App\Filters\Components\Default\Phone;
use App\Filters\Components\Default\Status;
use App\Filters\Components\Agency\AdvancePhone;
use App\Filters\Components\Agency\AdvanceStudentName;

class StudentFilter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            StudentID::class,
            IDName::class,
            Phone::class,
            Email::class,
            Country::class,
            AssaignUser::class,
            Status::class,
            Source::class,
            CreateAt::class,
            AdvanceStudentName::class,
            AdvancePhone::class,
        ];
    }
}
