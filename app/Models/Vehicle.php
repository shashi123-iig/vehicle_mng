<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
     protected $fillable = ['vehicle_number', 'vehicle_type', 'fuel_type', 'capacity'];

    public function assignments()
{
    return $this->hasMany(VehicleAssignment::class);
}

public function drivers()
{
    return $this->belongsToMany(Driver::class, 'vehicle_assignments');
}

}
