<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FuelType;

class FuelTypeSeeder extends Seeder
{
    public function run(): void
    {
        $fuelTypes = [
            ['name' => 'Regular', 'description' => 'Standard unleaded gasoline'],
            ['name' => 'Premium', 'description' => 'Higher octane unleaded gasoline'],
            ['name' => 'Diesel', 'description' => 'Diesel fuel for light and commercial vehicles'],
        ];

        foreach ($fuelTypes as $fuel) {
            FuelType::updateOrCreate(
                ['name' => $fuel['name']],
                $fuel
            );
        }
    }
}
