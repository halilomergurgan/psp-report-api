<?php

namespace Database\Seeders;

use App\Models\Acquirer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcquirerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Acquirer::factory(5)->create();
    }
}
