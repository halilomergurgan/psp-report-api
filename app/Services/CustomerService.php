<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\Interface\CustomerRepositoryInterface;

class CustomerService
{
    protected CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param string $transactionId
     * @return Customer|null
     */
    public function getCustomer(string $transactionId): ?Customer
    {
        return $this->customerRepository->getCustomer($transactionId);
    }
}
