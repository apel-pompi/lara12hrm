<?php

namespace App\Services\Default;

use App\Filters\Default\TransactionNameFilter;
use App\Models\Default\TransactionName;

class TransactionNameService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = TransactionName::with('user')->orderBy('id', 'DESC');

        $student = resolve(TransactionNameFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
