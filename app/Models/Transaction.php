<?php

namespace App\Models;

use Elastic\ScoutDriverPlus\Searchable;
use Filterable\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory, Filterable, Searchable;

    protected $primaryKey = 'transaction_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'transaction_id', 'merchant_id', 'status', 'operation', 'payment_method',
        'reference_no', 'custom_data', 'channel', 'acquirer_transaction_id',
        'fx_transaction_id', 'agent_info_id'
    ];

    /**
     * @return BelongsTo
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * @return BelongsTo
     */
    public function acquirer(): BelongsTo
    {
        return $this->belongsTo(Acquirer::class, 'acquirer_id');
    }

    /**
     * @return BelongsTo
     */
    public function fx(): BelongsTo
    {
        return $this->belongsTo(Fx::class, 'fx_id');
    }

    /**
     * @return BelongsTo
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->transaction_id)) {
                $model->transaction_id = (string) Str::uuid();
            }
        });
    }

    public function toSearchableArray()
    {
        return [
            'transaction_id' => $this->transaction_id,
            'merchant_id' => $this->merchant_id,
            'acquirer_id' => $this->acquirer_id,
            'fx.original_amount' => $this->fx->original_amount,
            'fx.original_currency' => $this->fx->original_currency,
            'created_at' => $this->created_at->toAtomString(),
        ];
    }
}
