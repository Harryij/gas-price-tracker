<?php

use App\Models\Brand;
use App\Models\FuelPrice;
use App\Models\FuelType;
use App\Models\Station;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $stations = Station::with(['brand', 'fuelPrices.fuelType'])
            ->orderBy('name')
            ->get();

        $brands = Brand::orderBy('name')->get();
        $fuelTypes = FuelType::orderBy('name')->get();

        $latestPrices = FuelPrice::with(['station.brand', 'fuelType'])
            ->latest('effective_at')
            ->latest()
            ->limit(12)
            ->get();

        $priceRange = [
            'min' => FuelPrice::min('price'),
            'max' => FuelPrice::max('price'),
        ];

        $databaseReady = true;
    } catch (Throwable $exception) {
        $stations = collect();
        $brands = collect();
        $fuelTypes = collect();
        $latestPrices = collect();
        $priceRange = ['min' => null, 'max' => null];
        $databaseReady = false;
    }

    return view('welcome', compact(
        'stations',
        'brands',
        'fuelTypes',
        'latestPrices',
        'priceRange',
        'databaseReady'
    ));
});
