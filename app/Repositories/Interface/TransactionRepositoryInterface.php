<?php

namespace App\Repositories\Interface;

use App\Models\Transaction;
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

    /**
     * @param string $transactionId
     * @return Transaction|null
     */
    public function getTransaction(string $transactionId): ?Transaction;
}
