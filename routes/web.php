<?php

use App\Models\Brand;
use App\Models\FuelPrice;
use App\Models\FuelType;
use App\Models\Station;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

        $latitudes = $stations->pluck('latitude')->map(fn ($value) => (float) $value);
        $longitudes = $stations->pluck('longitude')->map(fn ($value) => (float) $value);
        $minLatitude = $latitudes->min();
        $maxLatitude = $latitudes->max();
        $minLongitude = $longitudes->min();
        $maxLongitude = $longitudes->max();
        $latitudeRange = max(($maxLatitude ?? 0) - ($minLatitude ?? 0), 0.000001);
        $longitudeRange = max(($maxLongitude ?? 0) - ($minLongitude ?? 0), 0.000001);

        $mapStations = $stations->map(function (Station $station) use ($minLatitude, $maxLatitude, $minLongitude, $latitudeRange, $longitudeRange) {
            $brandName = $station->brand->name ?? 'Unknown brand';
            $brandLogo = $station->brand->logo;
            $fallbackLogo = 'brand-icons/'.Str::slug($brandName).'.svg';
            $mapLogo = 'brand-icons/'.Str::slug($brandName).'-map.png';
            $brandIconPath = $brandLogo && file_exists(public_path($brandLogo))
                ? $brandLogo
                : $fallbackLogo;
            $brandMapIconPath = file_exists(public_path($mapLogo))
                ? $mapLogo
                : $brandIconPath;
            $brandIconVersion = file_exists(public_path($brandIconPath))
                ? filemtime(public_path($brandIconPath))
                : time();
            $brandMapIconVersion = file_exists(public_path($brandMapIconPath))
                ? filemtime(public_path($brandMapIconPath))
                : time();

            $prices = $station->fuelPrices
                ->sortBy('fuelType.name')
                ->map(fn (FuelPrice $price) => [
                    'fuel' => $price->fuelType->name ?? 'Fuel',
                    'price' => number_format((float) $price->price, 2),
                    'rawPrice' => (float) $price->price,
                    'effectiveAt' => $price->effective_at
                        ? \Illuminate\Support\Carbon::parse($price->effective_at)->format('M d, Y')
                        : 'No date',
                ])
                ->values();

            $lowestPrice = $prices->min('rawPrice');

            return [
                'id' => $station->id,
                'name' => $station->name,
                'brand' => $brandName,
                'brandColor' => '#90EE90',
                'brandIcon' => asset($brandIconPath).'?v='.$brandIconVersion,
                'brandMapIcon' => asset($brandMapIconPath).'?v='.$brandMapIconVersion,
                'address' => $station->address,
                'city' => $station->city,
                'latitude' => (float) $station->latitude,
                'longitude' => (float) $station->longitude,
                'x' => 10 + ((((float) $station->longitude - $minLongitude) / $longitudeRange) * 80),
                'y' => 10 + (((($maxLatitude ?? (float) $station->latitude) - (float) $station->latitude) / $latitudeRange) * 80),
                'prices' => $prices,
                'lowestPrice' => $lowestPrice ? number_format($lowestPrice, 2) : null,
                'searchText' => strtolower($station->name.' '.$station->address.' '.$station->city.' '.($station->brand->name ?? '')),
                'fuelTypes' => $prices->pluck('fuel')->values(),
            ];
        })->values();

        $mapCenter = ['latitude' => 6.9214, 'longitude' => 122.0790];
        $mapBounds = [
            'southWest' => ['latitude' => 6.75, 'longitude' => 121.90],
            'northEast' => ['latitude' => 7.08, 'longitude' => 122.28],
        ];

        $databaseReady = true;
    } catch (Throwable $exception) {
        $stations = collect();
        $brands = collect();
        $fuelTypes = collect();
        $latestPrices = collect();
        $mapStations = collect();
        $mapCenter = ['latitude' => 6.9214, 'longitude' => 122.0790];
        $mapBounds = [
            'southWest' => ['latitude' => 6.75, 'longitude' => 121.90],
            'northEast' => ['latitude' => 7.08, 'longitude' => 122.28],
        ];
        $priceRange = ['min' => null, 'max' => null];
        $databaseReady = false;
    }

    return view('welcome', compact(
        'stations',
        'brands',
        'fuelTypes',
        'latestPrices',
        'mapStations',
        'mapCenter',
        'mapBounds',
        'priceRange',
        'databaseReady'
    ));
});
