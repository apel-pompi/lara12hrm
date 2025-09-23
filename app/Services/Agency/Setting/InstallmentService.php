<?php

namespace App\Services\Agency\Setting;

use App\Filters\Agency\Setting\InstallmentFilter;
use App\Models\Default\Installment;

class InstallmentService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Installment::with('user')->orderBy('id', 'DESC');

        $student = resolve(InstallmentFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
