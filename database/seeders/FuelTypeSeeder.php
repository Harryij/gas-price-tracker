<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FuelType;

class FuelTypeSeeder extends Seeder
{
    public function run(): void
    {

        $fuelTypes = [

            ['name'=>'Regular'],
            ['name'=>'Premium'],
            ['name'=>'Diesel'],

        ];

        foreach($fuelTypes as $fuel){

            FuelType::create($fuel);

        }

    }
}