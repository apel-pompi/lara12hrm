<?php

namespace App\Filters\User;

use App\Filters\BaseFilter;
use App\Filters\Components\Default\Email;
use App\Filters\Components\Default\Name;
use App\Filters\Components\Default\Username;

class UserPermissionFillter extends BaseFilter
{
    protected function getFilters(): array
    {
        return [
            Name::class,
            Username::class,
            Email::class

        ];
    }
}
