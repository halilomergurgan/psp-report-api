<?php

namespace App\Filters;

use Filterable\Filter;
use Illuminate\Database\Eloquent\Builder;

class TransactionFilter extends Filter
{
    protected array $filters = [
        'merchant_id', 'acquirer_id', 'status', 'operation',
        'payment_method', 'filterField', 'filterValue', 'fromDate', 'toDate'
    ];

    /**
     * @param $value
     * @return Builder
     */
    public function merchant_id($value): Builder
    {
        return $this->builder->where('transactions.merchant_id', $value);
    }

    /**
     * @param $value
     * @return Builder
     */
    public function acquirer_id($value): Builder
    {
        return $this->builder->where('transactions.acquirer_id', $value);
    }

    /**
     * @param $value
     * @return Builder
     */
    public function status($value): Builder
    {
        return $this->builder->where('transactions.status', $value);
    }

    /**
     * @param $value
     * @return Builder
     */
    public function operation($value): Builder
    {
        return $this->builder->where('transactions.operation', $value);
    }

    /**
     * @param $value
     * @return Builder
     */
    public function payment_method($value): Builder
    {
        return $this->builder->where('transactions.payment_method', $value);
    }

    /**
     * @param $value
     * @return Builder
     */
    public function fromDate($value): Builder
    {
        return $this->builder->where('transactions.created_at', '>=', $value);
    }

    /**
     * @param $value
     * @return Builder
     */
    public function toDate($value): Builder
    {
        return $this->builder->where('transactions.created_at', '<=', $value);
    }

    /**
     * @param $value
     * @return Builder
     */
    public function filterField($value): Builder
    {
        return match ($value) {
            'Transaction UUID' => $this->builder->where('transactions.transaction_id', request()->input('filterValue')),
            'Customer Email' => $this->builder->whereHas('customer', function ($query) {
                $query->where('email', request()->input('filterValue'));
            }),
            'Reference No' => $this->builder->where('transactions.reference_no', request()->input('filterValue')),
            'Custom Data' => $this->builder->where('transactions.custom_data', 'like', '%' . request()->input('filterValue') . '%'),
            'Card PAN' => $this->builder->whereHas('customer', function ($query) {
                $query->where('number', 'like', '%' . request()->input('filterValue') . '%');
            }),
            'Merchant Name' => $this->builder->whereHas('merchant', function ($query) {
                $query->where('name', 'like', '%' . request()->input('filterValue') . '%');
            }),
            'Acquirer Code' => $this->builder->whereHas('acquirer', function ($query) {
                $query->where('code', request()->input('filterValue'));
            }),
            'FX Currency' => $this->builder->whereHas('fx', function ($query) {
                $query->where('original_currency', request()->input('filterValue'));
            }),
            default => $this->builder,
        };
    }
}
