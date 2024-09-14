<?php

namespace App\AggregationServices;

class TransactionAggregationService
{
    public static function getTransactionReportAggregation($fromDate, $toDate, $merchantId, $acquirerId)
    {
        return [
            'index' => 'transactions',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['range' => ['created_at' => ['gte' => $fromDate, 'lte' => $toDate]]],
                            ['term' => ['merchant_id' => $merchantId]],
                            ['term' => ['acquirer_id' => $acquirerId]],
                        ]
                    ]
                ],
                'aggs' => [
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
                ]
            ]
        ];
    }

    public static function getTransactionAggregation(string $transactionId)
    {
        return [
            'index' => 'transactions',
            'body' => [
                'query' => [
                    'match' => [
                        'transaction_id' => $transactionId
                    ]
                ],
                '_source' => [
                    'transaction_id', 'merchant_id', 'status', 'operation', 'payment_method',
                    'reference_no', 'custom_data', 'channel', 'acquirer_id',
                    'fx.original_amount', 'fx.original_currency', 'created_at', 'updated_at'
                ]
            ]
        ];
    }
}
