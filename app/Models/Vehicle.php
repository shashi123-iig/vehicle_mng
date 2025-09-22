<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
     protected $fillable = ['model', 'number', 'fuel_type'];

    public function fuelEntries()
    {
        return $this->hasMany(FuelEntry::class);
    }

}
