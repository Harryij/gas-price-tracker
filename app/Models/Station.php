<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'brand_id',
        'name',
        'address',
        'city',
        'latitude',
        'longitude',
    ];
    
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function fuelPrices()
    {
        return $this->hasMany(FuelPrice::class);
    }
}