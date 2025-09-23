<?php

namespace App\Services\Default;

use App\Filters\Default\TransactionNoFilter;
use App\Models\Default\Transaction;

class TransactionNoService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Transaction::with('branch','user','transactionname')->orderBy('id', 'DESC');

        $student = resolve(TransactionNoFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        return $student;
    }
}
