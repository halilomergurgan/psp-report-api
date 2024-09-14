<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'transaction_id' => $this->transaction_id,
            'merchant_id' => $this->merchant_id,
            'status' => $this->status,
            'operation' => $this->operation,
            'payment_method' => $this->payment_method,
            'reference_no' => $this->reference_no,
            'custom_data' => $this->custom_data,
            'channel' => $this->channel,
            'acquirer_id' => $this->acquirer_id
        ];
    }
}
