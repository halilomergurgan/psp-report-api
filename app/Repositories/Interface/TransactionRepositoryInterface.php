<?php

namespace App\Repositories\Interface;

interface TransactionRepositoryInterface
{
    public function getTransactionsForReport($merchantId, $acquirerId, $fromDate, $toDate);

    public function getTransactionList($filters, $fromDate = null, $toDate = null);
}
