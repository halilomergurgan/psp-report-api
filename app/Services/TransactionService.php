<?php

namespace App\Services;

use App\Repositories\Interface\TransactionRepositoryInterface;

class TransactionService
{
    protected TransactionRepositoryInterface $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function getTransactionsForReport($merchantId, $acquirerId, $fromDate, $toDate)
    {
        return $this->transactionRepository->getTransactionsForReport($merchantId, $acquirerId, $fromDate, $toDate);
    }

    public function getTransactionList($filters)
    {
        return $this->transactionRepository->getTransactionList($filters);
    }
}
