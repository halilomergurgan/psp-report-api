<?php

namespace App\Services;

use App\Repositories\Interface\TransactionRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionService
{
    protected TransactionRepositoryInterface $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param $filters
     * @return array
     */
    public function transactionReport($filters): array
    {
        return $this->transactionRepository->transactionReport($filters);
    }

    /**
     * @param $filters
     * @return LengthAwarePaginator
     */
    public function getTransactionList($filters): LengthAwarePaginator
    {
        return $this->transactionRepository->getTransactionList($filters);
    }

    public function getTransaction(string $transactionId)
    {
        return $this->transactionRepository->getTransaction($transactionId);
    }
}
