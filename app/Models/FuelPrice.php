<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelPrice extends Model
{
    protected $fillable = [
        'station_id',
        'fuel_type_id',
        'price',
        'effective_at',
    ];
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class);
    }
}