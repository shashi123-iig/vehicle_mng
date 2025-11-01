<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelEntry extends Model
{
     protected $fillable = ['driver_id',
        'vehicle_id', 'liters', 'amount', 'photo_path'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
