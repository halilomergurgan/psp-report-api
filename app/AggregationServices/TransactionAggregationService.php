<?php

namespace App\AggregationServices;

class TransactionAggregationService
{
    /**
     * @param $fromDate
     * @param $toDate
     * @param $merchantId
     * @param $acquirerId
     * @return array
     */
    public static function getTransactionReportAggregation($fromDate, $toDate, $merchantId, $acquirerId): array
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

    /**
     * @param string $transactionId
     * @return array
     */
    public static function getTransactionAggregation(string $transactionId): array
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

    /**
     * @param string $transactionId
     * @return array
     */
    public static function getCustomerAggregation(string $transactionId): array
    {
        return [
            'index' => 'transactions',
            'body' => [
                'query' => [
                    'match' => [
                        'transaction_id' => $transactionId
                    ]
                ]
            ]
        ];
    }
}
