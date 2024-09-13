<?php

namespace Database\Seeders;

use App\Models\Fx;
use Illuminate\Database\Seeder;

class FxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fx::factory(50)->create();
    }
}
