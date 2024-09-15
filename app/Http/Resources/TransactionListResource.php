<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'fx' => [
                'merchant' => [
                    'originalAmount' => $this->fx->original_amount,
                    'originalCurrency' => $this->fx->original_currency,
                ],
            ],
            'customerInfo' => [
                'number' => $this->customer->number,
                'email' => $this->customer->email,
                'billingFirstName' => $this->customer->billing_first_name,
                'billingLastName' => $this->customer->billing_last_name,
            ],
            'merchant' => [
                'id' => $this->merchant->id,
                'name' => $this->merchant->name,
            ],
            'transaction' => [
                'merchant' => [
                    'referenceNo' => $this->reference_no,
                    'status' => $this->status,
                    'operation' => $this->operation,
                    'message' => $this->message ?? null,
                    'created_at' => $this->created_at,
                    'transactionId' => $this->transaction_id,
                ],
            ],
            'acquirer' => [
                'id' => $this->acquirer->id,
                'name' => $this->acquirer->name,
                'code' => $this->acquirer->code,
                'type' => $this->acquirer->type,
            ],
            'refundable' => true,
        ];
    }
}
