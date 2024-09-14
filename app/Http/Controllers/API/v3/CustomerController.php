<?php

namespace App\Http\Controllers\API\v3;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * @param CustomerRequest $request
     * @return JsonResponse
     */
    public function getCustomer(CustomerRequest $request): JsonResponse
    {
        $requestData = $request->validated();
        $customer = $this->customerService->getCustomer($requestData['transactionId']);

        if ($customer) {
            return response()->json([
                'status' => StatusEnum::APPROVED,
                'customerInfo' => new CustomerResource($customer),
            ]);
        }

        return response()->json([
            'status' => StatusEnum::DECLINED,
            'message' => 'Customer not found',
        ]);
    }
}
