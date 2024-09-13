<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

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
}
