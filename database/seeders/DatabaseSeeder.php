<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BrandSeeder::class,
            FuelTypeSeeder::class,
            StationSeeder::class,
            FuelPriceSeeder::class,
            PriceAdjustmentSeeder::class,
        ]);
    }
}
