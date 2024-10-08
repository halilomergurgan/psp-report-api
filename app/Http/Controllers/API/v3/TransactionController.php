<?php

namespace App\Http\Controllers\API\v3;

use App\Enums\StatusEnum;
use App\Filters\TransactionFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionListRequest;
use App\Http\Requests\TransactionReportRequest;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\PaginatedResource;
use App\Http\Resources\TransactionListResource;
use App\Http\Resources\TransactionResource;
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
     * @param TransactionListRequest $request
     * @return JsonResponse
     */
    public function transactionList(TransactionListRequest $request): JsonResponse
    {
        $filters = new TransactionFilter($request);

        $transactions = $this->transactionService->getTransactionList($filters);

        return response()->json(new PaginatedResource(TransactionListResource::collection($transactions)));
    }

    /**
     * @param TransactionRequest $request
     * @return JsonResponse
     */
    public function transaction(TransactionRequest $request): JsonResponse
    {
        $requestData = $request->validated();

        $transaction = $this->transactionService->getTransaction($requestData['transactionId']);

        if ($transaction) {
            return response()->json([
                'status' => StatusEnum::APPROVED,
                'data' =>  new TransactionResource($transaction)
            ]);
        } else {
            return response()->json([
                'status' => StatusEnum::DECLINED,
                'message' => 'Transaction not found'
            ], 404);
        }
    }
}
