<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelEntry extends Model
{
     protected $fillable = [
        'vehicle_id', 'driver_name', 'driver_mobile', 'liters', 'amount', 'photo_path'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
