<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Brand;

class StationSeeder extends Seeder
{
    public function run(): void
    {
        $shell = Brand::where('name', 'Shell')->first();
        $petron = Brand::where('name', 'Petron')->first();
        $caltex = Brand::where('name', 'Caltex')->first();
        $phoenix = Brand::where('name', 'Phoenix')->first();
        $seaoil = Brand::where('name', 'Seaoil')->first();


        $stations = [
            [
                'brand_id' => $shell->id,
                'name' => 'Shell Divisoria',
                'address' => 'Divisoria Road',
                'city' => 'Zamboanga City',
                'latitude' => 6.948241966253915,
                'longitude' => 122.10920892657074,
            ],

            [
                'brand_id' => $shell->id,
                'name' => 'Shell Veterans',
                'address' => 'Veterans Avenue',
                'city' => 'Zamboanga City',
                'latitude' => 6.9147843372218984,
                'longitude' => 122.07974308808545,
            ],

            [
                'brand_id' => $petron->id,
                'name' => 'Petron Tetuan',
                'address' => 'Tetuan Highway',
                'city' => 'Zamboanga City',
                'latitude' => 6.928226714623714,
                'longitude' => 122.08997666883874,
            ],

            [
                'brand_id' => $petron->id,
                'name' => 'Petron Gas Station',
                'address' => 'MCLL Highway',
                'city' => 'Zamboanga City',
                'latitude' => 6.927322822475103,
                'longitude' => 122.08527112086514,
            ],

            [
                'brand_id' => $caltex->id,
                'name' => 'Caltex Veterans',
                'address' => 'Veterans Avenue',
                'city' => 'Zamboanga City',
                'latitude' => 6.9184000,
                'longitude' => 122.0738000,
            ],

            [
                'brand_id' => $phoenix->id,
                'name' => 'Phoenix Tumaga',
                'address' => 'Tumaga Road',
                'city' => 'Zamboanga City',
                'latitude' => 6.9285000,
                'longitude' => 122.0607000,
            ],

            [
                'brand_id' => $seaoil->id,
                'name' => 'Seaoil Putik',
                'address' => 'Putik Road',
                'city' => 'Zamboanga City',
                'latitude' => 6.9346000,
                'longitude' => 122.0549000,
            ],
        ];

        foreach ($stations as $station) {
            Station::updateOrCreate(
                ['name' => $station['name']],
                $station
            );
        }
    }
}
