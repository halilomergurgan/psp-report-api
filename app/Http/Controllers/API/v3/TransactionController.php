<?php

namespace App\Http\Controllers\API\v3;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function report(Request $request)
    {
        try {
            $fromDate = Carbon::createFromFormat('Y-m-d', $request->fromDate)->startOfDay();
            $toDate = Carbon::createFromFormat('Y-m-d', $request->toDate)->endOfDay();

            $transactions = Transaction::whereBetween('transactions.created_at', [$fromDate, $toDate])
                ->where('transactions.merchant_id', $request->merchant)
                ->where('transactions.acquirer_id', $request->acquirer)
                ->join('fx', 'transactions.fx_id', '=', 'fx.id')
                ->selectRaw('count(*) as count, sum(fx.original_amount) as total, fx.original_currency as currency')
                ->groupBy('fx.original_currency')
                ->get();


            return response()->json([
                'status' => StatusEnum::APPROVED,
                'response' => $transactions
            ]);
        } catch (\Throwable $throwable) {
            return response()->json([
                'status' => StatusEnum::ERROR,
                'message' => $throwable->getMessage()
            ]);
        }
    }

    public function transactionList(Request $request)
    {
        try {

            $fromDate = $request->has('fromDate') ? Carbon::createFromFormat('Y-m-d', $request->fromDate)->startOfDay() : Carbon::now()->subMonth();
            $toDate = $request->has('toDate') ? Carbon::createFromFormat('Y-m-d', $request->toDate)->endOfDay() : Carbon::now();

            $transactions = Transaction::whereBetween('transactions.created_at', [$fromDate, $toDate]);

            if ($request->has('merchantId')) {
                $transactions->where('transactions.merchant_id', $request->merchantId);
            }

            if ($request->has('acquirerId')) {
                $transactions->where('transactions.acquirer_id', $request->acquirerId);
            }

            if ($request->has('status')) {
                $transactions->where('transactions.status', $request->status);
            }

            if ($request->has('operation')) {
                $transactions->where('transactions.operation', $request->operation);
            }

            if ($request->has('paymentMethod')) {
                $transactions->where('transactions.payment_method', $request->paymentMethod);
            }

            if ($request->has('filterField') && $request->has('filterValue')) {
                switch ($request->filterField) {
                    case 'Transaction UUID':
                        $transactions->where('transactions.transaction_id', $request->filterValue);
                        break;
                    case 'Customer Email':
                        $transactions->whereHas('customer', function ($query) use ($request) {
                            $query->where('email', $request->filterValue);
                        });
                        break;
                    case 'Reference No':
                        $transactions->where('transactions.reference_no', $request->filterValue);
                        break;
                    case 'Custom Data':
                        $transactions->where('transactions.custom_data', 'like', '%' . $request->filterValue . '%');
                        break;
                    case 'Card PAN':
                        $transactions->whereHas('customer', function ($query) use ($request) {
                            $query->where('number', 'like', '%' . $request->filterValue);
                        });
                        break;
                }
            }

            $transactions = $transactions->with(['fx', 'merchant', 'customer', 'acquirer'])
                ->paginate(50);

            return response()->json([
                'per_page' => $transactions->perPage(),
                'current_page' => $transactions->currentPage(),
                'next_page_url' => $transactions->nextPageUrl(),
                'prev_page_url' => $transactions->previousPageUrl(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
                'data' => $transactions->map(function ($transaction) {
                    return [
                        'fx' => [
                            'merchant' => [
                                'originalAmount' => $transaction->fx->original_amount,
                                'originalCurrency' => $transaction->fx->original_currency,
                            ],
                        ],
                        'customerInfo' => [
                            'number' => optional($transaction->customer)->number,
                            'email' => optional($transaction->customer)->email,
                            'billingFirstName' => optional($transaction->customer)->billing_first_name,
                            'billingLastName' => optional($transaction->customer)->billing_last_name,
                        ],
                        'merchant' => [
                            'id' => $transaction->merchant->id,
                            'name' => $transaction->merchant->name,
                        ],
                        'ipn' => [
                            'received' => $transaction->ipn_received ?? false,
                        ],
                        'transaction' => [
                            'merchant' => [
                                'referenceNo' => $transaction->reference_no,
                                'status' => $transaction->status,
                                'operation' => $transaction->operation,
                                'message' => $transaction->message ?? '',
                                'created_at' => $transaction->created_at,
                                'transactionId' => $transaction->transaction_id,
                            ],
                        ],
                        'acquirer' => [
                            'id' => $transaction->acquirer->id,
                            'name' => $transaction->acquirer->name,
                            'code' => $transaction->acquirer->code,
                            'type' => $transaction->acquirer->type,
                        ],
                        'refundable' => $transaction->refundable ?? false,
                    ];
                })
            ]);

        } catch (\Throwable $throwable) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $throwable->getMessage()
            ]);
        }
    }


}
