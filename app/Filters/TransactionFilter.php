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
        $filterValue = request()->input('filterVal') ?? null;

        return match ($value) {
            'Transaction UUID' => $this->builder->where('transactions.transaction_id', $filterValue),
            'Customer Email' => $this->builder->whereHas('customer', function ($query) use ($filterValue) {
                $query->where('email', $filterValue);
            }),
            'Reference No' => $this->builder->where('transactions.reference_no', $filterValue),
            'Custom Data' => $this->builder->where('transactions.custom_data', 'like', '%' . $filterValue . '%'),
            'Card PAN' => $this->builder->whereHas('customer', function ($query) use ($filterValue) {
                $query->where('number', 'like', '%' . $filterValue . '%');
            }),
            'Merchant Name' => $this->builder->whereHas('merchant', function ($query) use ($filterValue) {
                $query->where('name', 'like', '%' . $filterValue . '%');
            }),
            'Acquirer Code' => $this->builder->whereHas('acquirer', function ($query) use ($filterValue) {
                $query->where('code', $filterValue);
            }),
            'FX Currency' => $this->builder->whereHas('fx', function ($query) use ($filterValue) {
                $query->where('original_currency', $filterValue);
            }),
            default => $this->builder,
        };
    }


}
