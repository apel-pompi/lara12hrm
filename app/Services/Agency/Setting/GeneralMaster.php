<?php

namespace App\Services\Agency\Setting;

use App\Filters\Agency\Setting\GeneralMasterFiltter;
use App\Models\AgencySetting\MasterCategory;

class GeneralMaster
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = MasterCategory::with('user')->orderBy('id', 'DESC');

        $student = resolve(GeneralMasterFiltter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
