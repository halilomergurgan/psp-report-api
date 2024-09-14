<?php

namespace App\Repositories\Repository;

use App\AggregationServices\TransactionAggregationService;
use App\Models\Transaction;
use App\Repositories\Interface\TransactionRepositoryInterface;
use Carbon\Carbon;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Elasticsearch\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $filters
     * @return array[]
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function transactionReport($filters): array
    {
        $fromDate = Carbon::createFromFormat('Y-m-d', $filters['fromDate'])->startOfDay()->toAtomString();
        $toDate = Carbon::createFromFormat('Y-m-d', $filters['toDate'])->endOfDay()->toAtomString();

        $params = TransactionAggregationService::getTransactionReportAggregation($fromDate, $toDate, $filters['merchant'], $filters['acquirer']);

        $response = $this->client->search($params);

        $currencies = $response['aggregations']['currency_group']['buckets'];
        $results = [];

        foreach ($currencies as $currency) {
            $results[] = [
                'count' => $currency['transaction_count']['value'],
                'total' => number_format($currency['total_amount']['value'], 2),
                'currency' => $currency['key']
            ];
        }

        return [
            'response' => $results
        ];
    }

    /**
     * @param $filters
     * @return LengthAwarePaginator
     */
    public function getTransactionList($filters): LengthAwarePaginator
    {
        $transactions = Transaction::filter($filters)->with(['fx', 'merchant', 'customer', 'acquirer']);

        return $transactions->paginate(50);
    }

    /**
     * @param string $transactionId
     * @return Transaction|null
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function getTransaction(string $transactionId): ?Transaction
    {
        $params = TransactionAggregationService::getTransactionAggregation($transactionId);

        $response = $this->client->search($params);

        if (!empty($response['hits']['hits'])) {
            $transactionData = $response['hits']['hits'][0]['_source'];
            return new Transaction($transactionData);
        }

        return null;
    }
}
