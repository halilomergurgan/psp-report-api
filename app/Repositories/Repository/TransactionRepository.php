<?php

namespace App\Repositories\Repository;

use App\Models\Transaction;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getTransactionsForReport($merchantId, $acquirerId, $fromDate, $toDate)
    {
        $fromDate = Carbon::createFromFormat('Y-m-d', $fromDate)->startOfDay();
        $toDate = Carbon::createFromFormat('Y-m-d', $toDate)->endOfDay();

        return Transaction::whereBetween('transactions.created_at', [$fromDate, $toDate])
            ->where('transactions.merchant_id', $merchantId)
            ->where('transactions.acquirer_id', $acquirerId)
            ->join('fx', 'transactions.fx_id', '=', 'fx.id')
            ->selectRaw('count(*) as count, sum(fx.original_amount) as total, fx.original_currency as currency')
            ->groupBy('fx.original_currency')
            ->get();
    }

    public function getTransactionList($filters)
    {
        $transactions = Transaction::filter($filters)->with(['fx', 'merchant', 'customer', 'acquirer']);

        return $transactions->paginate(50);
    }

}
