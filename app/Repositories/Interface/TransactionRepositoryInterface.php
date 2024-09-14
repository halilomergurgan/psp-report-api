<?php

namespace App\Repositories\Interface;

use Illuminate\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface
{
    /**
     * @param $filters
     * @return array
     */
    public function transactionReport($filters): array;

    /**
     * @param $filters
     * @return LengthAwarePaginator
     */
    public function getTransactionList($filters): LengthAwarePaginator;
}
