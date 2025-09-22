<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelEntry extends Model
{
      protected $fillable = [
        'vehicle_id','date','fuel_price','fuel_quantity',
        'payment_mode','kilometer_reading','note',
        'image_vehicle_no','image_odometer','image_fuel_meter'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

}
