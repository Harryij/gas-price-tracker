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
                'name' => 'Shell Veterans Avenue Extension',
                'address' => 'Veterans Avenue Extension',
                'city' => 'Zamboanga City',
                'latitude' => 6.9147843372218984,
                'longitude' => 122.07974308808545,
            ],

            [
                'brand_id' => $shell->id,
                'name' => 'Shell Veterans Avenue',
                'address' => 'Veterans Avenue',
                'city' => 'Zamboanga City',
                'latitude' => 6.913544107790088,
                'longitude' => 122.07946638171265,
            ],

            [
                'brand_id' => $shell->id,
                'name' => 'Shell Nuñez Extension',
                'address' => 'Nuñez Extension',
                'city' => 'Zamboanga City',
                'latitude' => 6.916178904923128,
                'longitude' => 122.07607881214317,
            ],

            [
                'brand_id' => $shell->id,
                'name' => 'Shell Canelar',
                'address' => 'Canelar',
                'city' => 'Zamboanga City',
                'latitude' => 6.912618226244901,
                'longitude' => 122.07249202983515,
            ],

            [
                'brand_id' => $shell->id,
                'name' => 'Shell Governor Alvarez',
                'address' => 'Governor Alvarez',
                'city' => 'Zamboanga City',
                'latitude' => 6.909428969648764,
                'longitude' => 122.07045663151824,
            ],

            [
                'brand_id' => $shell->id,
                'name' => 'Shell Governor Camins Avenue',
                'address' => 'Governor Camins Avenue',
                'city' => 'Zamboanga City',
                'latitude' => 6.919858083784958,
                'longitude' => 122.06729579265048,
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
                'name' => 'Petron Veterans',
                'address' => 'Veterans Avenue',
                'city' => 'Zamboanga City',
                'latitude' => 6.920036482692739,
                'longitude' => 122.07926578031922,
            ],

            [
                'brand_id' => $petron->id,
                'name' => 'Petron (MCLL Hwy)',
                'address' => 'MCLL Highway',
                'city' => 'Zamboanga City',
                'latitude' => 6.927322822475103,
                'longitude' => 122.08527112086514,
            ],

            [
                'brand_id' => $petron->id,
                'name' => 'Petron (Fronting Baliwasan Brgy Hall)',
                'address' => 'Baliwasan Crossing',
                'city' => 'Zamboanga City',
                'latitude' => 6.915981313682601,
                'longitude' => 122.06015941983887,
            ],

            [
                'brand_id' => $petron->id,
                'name' => 'Petron (Gov Ramos Avenue)',
                'address' => 'Governor Ramos Avenue',
                'city' => 'Zamboanga City',
                'latitude' => 6.930004081978395,
                'longitude' => 122.06364420903266,
            ],

            [
                'brand_id' => $petron->id,
                'name' => 'Petron Tumaga',
                'address' => 'Tumaga Road',
                'city' => 'Zamboanga City',
                'latitude' => 6.933786276583844,
                'longitude' => 122.07871234825913,
            ],

            [
                'brand_id' => $petron->id,
                'name' => 'Petron (San Jose Road)',
                'address' => 'San Jose Road',
                'city' => 'Zamboanga City',
                'latitude' => 6.908741012075714,
                'longitude' => 122.0708741894429,
            ],

            [
                'brand_id' => $petron->id,
                'name' => 'Petron Centenarian',
                'address' => 'Gov Camins Rd',
                'city' => 'Zamboanga City',
                'latitude' => 6.921508893033749,
                'longitude' => 122.07716032483263,
            ],

            [
                'brand_id' => $caltex->id,
                'name' => 'Caltex Nuñez',
                'address' => 'Vitaliano Agan Aveneu Extension',
                'city' => 'Zamboanga City',
                'latitude' => 6.925015186696649,
                'longitude' => 122.07480331150636,
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
