<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceAdjustment extends Model
{
    protected $fillable = [
        'fuel_type_id',
        'adjustment',
        'direction',
        'effective_date',
        'announcement',
    ];
    public function fuelType()
    {
        return $this->belongsTo(FuelType::class);
    }
}