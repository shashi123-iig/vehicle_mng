<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FuelEntry;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;

class FuelEntryController extends Controller
{
   // Driver: add fuel entry
    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_mobile' => 'required|string',
            'liters' => 'required|numeric',
            'amount' => 'required|numeric',
            'fuel_type' => 'nullable|string',
        ]);

        // Find active vehicle assignment
        $assignment = VehicleAssignment::where('driver_mobile', $validated['driver_mobile'])
            ->where('active', true)
            ->first();

        if (!$assignment) {
            return response()->json([
                'status' => false,
                'message' => 'No active vehicle assigned to this driver',
            ]);
        }

        $entry = FuelEntry::create([
            'vehicle_id' => $assignment->vehicle_id,
            'driver_mobile' => $validated['driver_mobile'],
            'liters' => $validated['liters'],
            'amount' => $validated['amount'],
            'fuel_type' => $validated['fuel_type'] ?? $assignment->vehicle->fuel_type,
            'entry_date' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Fuel entry recorded successfully',
            'data' => $entry,
        ]);
    }

    // Admin: view all fuel entries
    public function report()
    {
        $data = FuelEntry::with('vehicle')->orderBy('entry_date', 'desc')->get();

        return response()->json([
            'status' => true,
            'report' => $data,
        ]);
    }

    // Admin: filter by vehicle
    public function reportByVehicle($vehicleId)
    {
        $data = FuelEntry::with('vehicle')
            ->where('vehicle_id', $vehicleId)
            ->orderBy('entry_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'report' => $data,
        ]);
    }
}
