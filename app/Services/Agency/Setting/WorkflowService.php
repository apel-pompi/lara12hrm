<?php

namespace App\Services\Agency\Setting;

use App\Filters\Agency\Setting\AcademicFilter;

use App\Models\AgencySetting\Workflow;

class WorkflowService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Workflow::with(['user'])->orderBy('id', 'desc');

        $workflow = resolve(AcademicFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $workflow;
    }
}
