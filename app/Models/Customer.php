<?php

namespace App\Models;

use Elastic\ScoutDriverPlus\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'transaction_id', 'number', 'expiry_month', 'expiry_year', 'email',
        'billing_first_name', 'billing_last_name', 'billing_address1', 'billing_city',
        'billing_postcode', 'billing_country', 'shipping_first_name', 'shipping_last_name',
        'shipping_address1', 'shipping_city', 'shipping_postcode', 'shipping_country'
    ];

    /**
     * @return BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function toSearchableArray()
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
            'created_at' => $this->created_at->toAtomString(),
            'updated_at' => $this->updated_at->toAtomString(),
        ];
    }
}
