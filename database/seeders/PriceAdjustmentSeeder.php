<?php

namespace Database\Seeders;

use App\Models\FuelType;
use App\Models\PriceAdjustment;
use Illuminate\Database\Seeder;

class PriceAdjustmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adjustments = [
            [
                'fuel' => 'Regular',
                'adjustment' => 0.35,
                'direction' => 'increase',
                'announcement' => 'Placeholder weekly adjustment for regular gasoline.',
            ],
            [
                'fuel' => 'Premium',
                'adjustment' => 0.45,
                'direction' => 'increase',
                'announcement' => 'Placeholder weekly adjustment for premium gasoline.',
            ],
            [
                'fuel' => 'Diesel',
                'adjustment' => 0.25,
                'direction' => 'decrease',
                'announcement' => 'Placeholder weekly adjustment for diesel.',
            ],
        ];

        foreach ($adjustments as $adjustment) {
            $fuelType = FuelType::where('name', $adjustment['fuel'])->first();

            if (! $fuelType) {
                continue;
            }

            PriceAdjustment::updateOrCreate(
                [
                    'fuel_type_id' => $fuelType->id,
                    'effective_date' => now()->toDateString(),
                ],
                [
                    'adjustment' => $adjustment['adjustment'],
                    'direction' => $adjustment['direction'],
                    'announcement' => $adjustment['announcement'],
                ]
            );
        }
    }
}
