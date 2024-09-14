<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'expiryMonth' => $this->expiry_month,
            'expiryYear' => $this->expiry_year,
            'email' => $this->email,
            'billingFirstName' => $this->billing_first_name,
            'billingLastName' => $this->billing_last_name,
            'billingAddress1' => $this->billing_address1,
            'billingCity' => $this->billing_city,
            'billingPostcode' => $this->billing_postcode,
            'billingCountry' => $this->billing_country,
            'shippingFirstName' => $this->shipping_first_name,
            'shippingLastName' => $this->shipping_last_name,
            'shippingAddress1' => $this->shipping_address1,
            'shippingCity' => $this->shipping_city,
            'shippingPostcode' => $this->shipping_postcode,
            'shippingCountry' => $this->shipping_country,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
