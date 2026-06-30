<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Shell', 'logo' => 'brand-icons/shell.png', 'color' => '#FFD500'],
            ['name' => 'Petron', 'logo' => 'brand-icons/petron.png', 'color' => '#0057B8'],
            ['name' => 'Caltex', 'logo' => 'brand-icons/caltex.jpg', 'color' => '#E30613'],
            ['name' => 'Seaoil', 'logo' => 'brand-icons/seaoil.png', 'color' => '#003DA5'],
            ['name' => 'Phoenix', 'logo' => 'brand-icons/phoenix.jpg', 'color' => '#FF6600'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['name' => $brand['name']],
                $brand
            );
        }
    }
}
