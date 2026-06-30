<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];
    public function fuelPrices()
    {
        return $this->hasMany(FuelPrice::class);
    }

    public function priceAdjustments()
    {
        return $this->hasMany(PriceAdjustment::class);
    }
}