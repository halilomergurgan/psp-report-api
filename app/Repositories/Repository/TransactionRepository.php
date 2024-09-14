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

    public function getTransactionList($filters, $fromDate = null, $toDate = null)
    {
        $fromDate = $fromDate ?: Carbon::now()->subMonth();
        $toDate = $toDate ?: Carbon::now();

        $transactions = Transaction::whereBetween('transactions.created_at', [$fromDate, $toDate]);

        if (isset($filters['merchantId'])) {
            $transactions->where('transactions.merchant_id', $filters['merchantId']);
        }

        if (isset($filters['acquirerId'])) {
            $transactions->where('transactions.acquirer_id', $filters['acquirerId']);
        }

        if (isset($filters['status'])) {
            $transactions->where('transactions.status', $filters['status']);
        }

        if (isset($filters['operation'])) {
            $transactions->where('transactions.operation', $filters['operation']);
        }

        if (isset($filters['paymentMethod'])) {
            $transactions->where('transactions.payment_method', $filters['paymentMethod']);
        }

        if (isset($filters['filterField']) && isset($filters['filterValue'])) {
            switch ($filters['filterField']) {
                case 'Transaction UUID':
                    $transactions->where('transactions.transaction_id', $filters['filterValue']);
                    break;
                case 'Customer Email':
                    $transactions->whereHas('customer', function ($query) use ($filters) {
                        $query->where('email', $filters['filterValue']);
                    });
                    break;
                case 'Reference No':
                    $transactions->where('transactions.reference_no', $filters['filterValue']);
                    break;
                case 'Custom Data':
                    $transactions->where('transactions.custom_data', 'like', '%' . $filters['filterValue'] . '%');
                    break;
                case 'Card PAN':
                    $transactions->whereHas('customer', function ($query) use ($filters) {
                        $query->where('number', 'like', '%' . $filters['filterValue']);
                    });
                    break;
                case 'Merchant Name':
                    $transactions->whereHas('merchant', function ($query) use ($filters) {
                        $query->where('name', 'like', '%' . $filters['filterValue'] . '%');
                    });
                    break;
                case 'Acquirer Code':
                    $transactions->whereHas('acquirer', function ($query) use ($filters) {
                        $query->where('code', $filters['filterValue']);
                    });
                    break;
                case 'FX Currency':
                    $transactions->whereHas('fx', function ($query) use ($filters) {
                        $query->where('original_currency', $filters['filterValue']);
                    });
                    break;
                default:
                    break;
            }
        }

        return $transactions->with(['fx', 'merchant', 'customer', 'acquirer'])
            ->paginate(50);
    }

}
