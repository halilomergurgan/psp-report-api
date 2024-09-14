<?php

namespace App\Http\Controllers\API\v3;

use App\Enums\StatusEnum;
use App\Filters\TransactionFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionReportRequest;
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
     * @param TransactionReportRequest $request
     * @return JsonResponse
     */
    public function transactionReport(TransactionReportRequest $request): JsonResponse
    {
        $filters = $request->validated();

        $transactions = $this->transactionService->transactionReport($filters);

        return response()->json(['status' => StatusEnum::APPROVED, 'response' => $transactions]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function transactionList(Request $request): JsonResponse
    {
        $filters = new TransactionFilter($request);

        $transactions = $this->transactionService->getTransactionList($filters);

        return response()->json([
            'status' => StatusEnum::APPROVED,
            'data' => $transactions
        ]);
    }
}
