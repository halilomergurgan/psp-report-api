<?php

namespace App\AggregationServices;

class TransactionAggregationService
{
    public static function getAggregations()
    {
        return [
            'currency_group' => [
                'terms' => ['field' => 'fx.original_currency'],
                'aggs' => [
                    'total_amount' => [
                        'sum' => ['field' => 'fx.original_amount']
                    ],
                    'transaction_count' => [
                        'value_count' => ['field' => 'fx.original_amount']
                    ]
                ]
            ],
        ];
    }
}
