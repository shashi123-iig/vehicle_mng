<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Driver extends Model
{
      use Notifiable, HasFactory;

    protected $fillable = ['name', 'mobile', 'status', 'vehicle_id'];

    public function vehicleAssignments()
{
    return $this->hasMany(VehicleAssignment::class);
}

public function vehicles()
{
    return $this->belongsToMany(Vehicle::class, 'vehicle_assignments');
}

}
