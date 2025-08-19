<?php

namespace App\Services;

use App\Filters\PersonalInfoFilter;
use App\Models\PersonalInfo;

class PersonalInfoService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = PersonalInfo::with('branch','designation','department')->orderBy('id', 'DESC');

        $attendance = resolve(PersonalInfoFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $attendance;
    }
}
