<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(MerchantSeeder::class);
        $this->call(AcquirerSeeder::class);
        $this->call(AgentSeeder::class);
        $this->call(TransactionSeeder::class);
    }
}
