<?php

namespace App\Repositories\Interface;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
}
