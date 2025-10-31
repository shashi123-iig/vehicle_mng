<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleAssignment extends Model
{
    //
       protected $fillable = ['vehicle_id', 'driver_name', 'driver_mobile', 'active'];

public function driver()
{
    return $this->belongsTo(Driver::class);
}

public function vehicle()
{
    return $this->belongsTo(Vehicle::class);
}

}
