<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'color',
    ];
    public function stations()
    {
        return $this->hasMany(Station::class);
    }
}
