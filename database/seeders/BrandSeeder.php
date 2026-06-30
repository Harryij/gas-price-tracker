<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => 'Shell',
            'logo' => 'shell.png',
            'color' => '#FFD500'
        ]);

        Brand::create([
            'name' => 'Petron',
            'logo' => 'petron.png',
            'color' => '#0057B8'
        ]);

        Brand::create([
            'name' => 'Caltex',
            'logo' => 'caltex.png',
            'color' => '#E30613'
        ]);

        Brand::create([
            'name' => 'Seaoil',
            'logo' => 'seaoil.png',
            'color' => '#003DA5'
        ]);

        Brand::create([
            'name' => 'Phoenix',
            'logo' => 'phoenix.png',
            'color' => '#FF6600'
        ]);
    }
}
