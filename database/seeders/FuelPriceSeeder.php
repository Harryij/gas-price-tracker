<?php

namespace Database\Seeders;

use App\Models\FuelPrice;
use App\Models\FuelType;
use App\Models\Station;
use Illuminate\Database\Seeder;

class FuelPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = [
            'Shell Divisoria' => ['Regular' => 63.25, 'Premium' => 68.40, 'Diesel' => 58.70],
            'Shell Veterans Avenue Extension' => ['Regular' => 63.55, 'Premium' => 58.75, 'Diesel' => 58.95],
            'Shell Veterans Avenue' => ['Regular' => 63.55, 'Premium' => 58.75, 'Diesel' => 58.95],
            'Shell Nuñez Extension' => ['Regular' => 63.55, 'Premium' => 58.75, 'Diesel' => 58.95],
            'Shell Canelar' => ['Regular' => 63.55, 'Premium' => 58.75, 'Diesel' => 58.95],
            'Shell Governor Camins Avenue' => ['Regular' => 63.55, 'Premium' => 58.75, 'Diesel' => 58.95],
            'Shell Governor Alvarez' => ['Regular' => 63.55, 'Premium' => 58.75, 'Diesel' => 58.95],
            'Petron Tetuan' => ['Regular' => 62.90, 'Premium' => 67.80, 'Diesel' => 58.40],
            'Petron Tumaga' => ['Regular' => 82.90, 'Premium' => 67.80, 'Diesel' => 58.40],
            'Petron (Gov Ramos Avenue)' => ['Regular' => 62.90, 'Premium' => 67.80, 'Diesel' => 58.40],
            'Petron (Fronting Baliwasan Brgy Hall)' => ['Regular' => 62.90, 'Premium' => 67.80, 'Diesel' => 58.40],
            'Petron Centenarian' => ['Regular' => 62.90, 'Premium' => 67.80, 'Diesel' => 58.40],
            'Petron Veterans' => ['Regular' => 62.90, 'Premium' => 67.80, 'Diesel' => 58.40],
            'Petron (MCLL Hwy)' => ['Regular' => 62.75, 'Premium' => 67.65, 'Diesel' => 58.20],
            'Petron (San Jose Road)' => ['Regular' => 62.75, 'Premium' => 67.65, 'Diesel' => 58.20],
            'Caltex Nuñez' => ['Regular' => 63.10, 'Premium' => 72.05, 'Diesel' => 58.55],
            'Phoenix Tumaga' => ['Regular' => 62.45, 'Premium' => 67.30, 'Diesel' => 80.95],
            'Seaoil Putik' => ['Regular' => 62.35, 'Premium' => 67.15, 'Diesel' => 52.85],
        ];

        foreach ($prices as $stationName => $fuelPrices) {
            $station = Station::where('name', $stationName)->first();

            if (! $station) {
                continue;
            }

            foreach ($fuelPrices as $fuelName => $price) {
                $fuelType = FuelType::where('name', $fuelName)->first();

                if (! $fuelType) {
                    continue;
                }

                FuelPrice::updateOrCreate(
                    [
                        'station_id' => $station->id,
                        'fuel_type_id' => $fuelType->id,
                    ],
                    [
                        'price' => $price,
                        'effective_at' => now()->subHours(match ($fuelName) {
                            'Regular' => 2,
                            'Premium' => 4,
                            default => 6,
                        }),
                    ]
                );
            }
        }
    }
}
