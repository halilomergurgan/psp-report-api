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
}
