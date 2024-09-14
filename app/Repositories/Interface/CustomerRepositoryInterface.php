<?php

namespace App\Repositories\Interface;

use App\Models\Customer;

interface CustomerRepositoryInterface
{
    /**
     * @param string $transactionId
     * @return Customer|null
     */
    public function getCustomer(string $transactionId): ?Customer;
}
