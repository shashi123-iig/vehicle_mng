<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FuelEntry;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class FuelEntryController extends Controller
{
    /**
     * Store a new fuel entry (Driver)
     */
    // public function store(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'driver_mobile' => 'required|string',
    //             'liters' => 'required|numeric',
    //             'amount' => 'required|numeric',
    //             'fuel_type' => 'nullable|string',
    //         ]);

    //         // Find active vehicle assignment
    //         $assignment = VehicleAssignment::where('driver_mobile', $validated['driver_mobile'])
    //             ->where('active', true)
    //             ->with('vehicle')
    //             ->first();

    //         if (!$assignment) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'No active vehicle assigned to this driver.',
    //             ], 404);
    //         }

    //         // Create fuel entry
    //         $entry = FuelEntry::create([
    //             'vehicle_id' => $assignment->vehicle_id,
    //             'driver_mobile' => $validated['driver_mobile'],
    //             'liters' => $validated['liters'],
    //             'amount' => $validated['amount'],
    //             'fuel_type' => $validated['fuel_type'] ?? $assignment->vehicle->fuel_type ?? 'unknown',
    //             'entry_date' => now(),
    //         ]);

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Fuel entry recorded successfully.',
    //             'data' => $entry,
    //         ], 201);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation failed.',
    //             'errors' => $e->errors(),
    //         ], 422);

    //     } catch (Exception $e) {
    //         Log::error('Fuel entry creation failed: ' . $e->getMessage());

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something went wrong. Please try again later.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'driver_id' => 'required|integer',
                'driver_mobile' => 'required|string',
                'liters' => 'required|numeric',
                'amount' => 'required|numeric',
                'photo' => 'nullable|image|max:2048',
            ]);

            // Find active vehicle assignment by driver_mobile
            $assignment = VehicleAssignment::where('driver_mobile', $validated['driver_mobile'])
                ->where('active', true)
                ->with('vehicle')
                ->first();

            if (!$assignment) {
                return response()->json([
                    'status' => false,
                    'message' => 'No active vehicle assigned to this driver.',
                ], 404);
            }

            // Handle optional photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('fuel_photos', 'public');
            }

            // Create fuel entry
            $entry = FuelEntry::create([
                'driver_id' => $validated['driver_id'],
                'vehicle_id' => $assignment->vehicle_id,
                'liters' => $validated['liters'],
                'amount' => $validated['amount'],
                'photo' => $photoPath,
                'date' => now()->toDateString(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Fuel entry recorded successfully.',
                'data' => $entry,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            Log::error('Fuel entry creation failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Admin: view all fuel entries
     */
    public function report()
    {
        try {
            $data = FuelEntry::with('vehicle')
                ->orderBy('entry_date', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'report' => $data,
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching fuel report: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch report. Try again later.',
            ], 500);
        }
    }

    /**
     * Admin: filter fuel entries by vehicle ID
     */
    public function reportByVehicle($vehicleId)
    {
        try {
            $data = FuelEntry::with('vehicle')
                ->where('vehicle_id', $vehicleId)
                ->orderBy('entry_date', 'desc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No fuel entries found for this vehicle.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'report' => $data,
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching vehicle report: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while fetching data.',
            ], 500);
        }
    }
}
