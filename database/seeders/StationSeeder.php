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
                'latitude' => 6.9134950,
                'longitude' => 122.0798100,
            ],

            [
                'brand_id' => $shell->id,
                'name' => 'Shell Veterans',
                'address' => 'Veterans Avenue',
                'city' => 'Zamboanga City',
                'latitude' => 6.9213000,
                'longitude' => 122.0789000,
            ],

            [
                'brand_id' => $petron->id,
                'name' => 'Petron Tetuan',
                'address' => 'Tetuan Highway',
                'city' => 'Zamboanga City',
                'latitude' => 6.9218000,
                'longitude' => 122.0912000,
            ],

            [
                'brand_id' => $petron->id,
                'name' => 'Petron Guiwan',
                'address' => 'Guiwan Highway',
                'city' => 'Zamboanga City',
                'latitude' => 6.8971000,
                'longitude' => 122.0841000,
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

            Station::create($station);

        }
    }
}