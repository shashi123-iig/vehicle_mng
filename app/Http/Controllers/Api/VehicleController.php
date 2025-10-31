<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
     // Admin: add new vehicle
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_number' => 'required|unique:vehicles',
            'vehicle_type' => 'nullable|string',
            'fuel_type' => 'nullable|string',
            'capacity' => 'nullable|integer',
        ]);

        $vehicle = Vehicle::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Vehicle added successfully',
            'data' => $vehicle,
        ]);
    }

    // Admin: assign driver manually
    public function assignDriver(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_name' => 'required|string',
            'driver_mobile' => 'required|string',
        ]);

        // Deactivate any previous driver for this vehicle
        VehicleAssignment::where('vehicle_id', $validated['vehicle_id'])
            ->update(['active' => false]);

        // Create new active assignment
        $assignment = VehicleAssignment::create([
            'vehicle_id' => $validated['vehicle_id'],
            'driver_name' => $validated['driver_name'],
            'driver_mobile' => $validated['driver_mobile'],
            'active' => true,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Driver assigned successfully',
            'data' => $assignment,
        ]);
    }

    // Admin: list all vehicles with current driver
    public function index()
    {
        $vehicles = Vehicle::with('activeAssignment')->get();

        return response()->json([
            'status' => true,
            'data' => $vehicles,
        ]);
    }
}
