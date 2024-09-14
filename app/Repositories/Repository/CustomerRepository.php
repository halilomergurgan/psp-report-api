<?php

namespace App\Repositories\Repository;

use App\AggregationServices\TransactionAggregationService;
use App\Models\Customer;
use App\Repositories\Interface\CustomerRepositoryInterface;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Elasticsearch\Client;

class CustomerRepository implements CustomerRepositoryInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $transactionId
     * @return Customer|null
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function getCustomer(string $transactionId): ?Customer
    {
        $params = TransactionAggregationService::getCustomerAggregation($transactionId);

        $response = $this->client->search($params);

        if (!empty($response['hits']['hits'])) {
            $transactionData = $response['hits']['hits'][0]['_source'];

            if (isset($transactionData['customer_id'])) {
                return Customer::find($transactionData['customer_id']);
            } else {
                return null;
            }
        }

        return null;
    }

}
