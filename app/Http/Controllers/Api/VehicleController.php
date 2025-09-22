<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
     public function index()
    {
        $vehicles = Vehicle::all();
        return response()->json([
            'success' => true,
            'message' => 'Vehicle list fetched successfully',
            'data' => $vehicles
        ]);
    }

    public function store(Request $request)
    {
        $vehicle = Vehicle::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Vehicle created successfully',
            'data' => $vehicle
        ], 201);
    }

    public function show($id)
    {
        $vehicle = Vehicle::with('fuelEntries')->findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Vehicle details fetched successfully',
            'data' => $vehicle
        ]);
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully',
            'data' => $vehicle
        ]);
    }

    public function destroy($id)
    {
        Vehicle::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully'
        ]);
    }

}
