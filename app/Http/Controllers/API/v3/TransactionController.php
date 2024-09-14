<?php

namespace App\Http\Controllers\API\v3;

use App\Enums\StatusEnum;
use App\Filters\TransactionFilter;
use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function report(Request $request): JsonResponse
    {
        try {
            $transactions = $this->transactionService->getTransactionsForReport(
                $request->merchant,
                $request->acquirer,
                $request->fromDate,
                $request->toDate
            );

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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function transactionList(Request $request): JsonResponse
    {
        try {
            $filters = new TransactionFilter($request);

            $transactions = $this->transactionService->getTransactionList($filters);

            return response()->json([
                'status' => StatusEnum::APPROVED,
                'data' => $transactions
            ]);
        } catch (\Throwable $throwable) {
            return response()->json([
                'status' => StatusEnum::ERROR,
                'message' => $throwable->getMessage()
            ]);
        }
    }
}
