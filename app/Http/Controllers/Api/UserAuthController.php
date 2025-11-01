<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class UserAuthController extends Controller
{
    /**
     * Driver login using mobile number only (Sanctum token)
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'mobile' => ['required', 'string']
            ]);

            // Find active vehicle assignment
            $assignment = VehicleAssignment::where('driver_mobile', $validated['mobile'])
                ->where('active', true)
                ->with('vehicle')
                ->first();

            if (!$assignment) {
                return response()->json([
                    'status' => false,
                    'message' => 'No active vehicle found for this mobile number.'
                ], 404);
            }

            // Create or get driver record
            $driver = Driver::firstOrCreate(
                ['mobile' => $assignment->driver_mobile],
                ['name' => $assignment->driver_name]
            );

            // Generate Sanctum token
            $token = $driver->createToken('driver_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token,
                'driver' => [
                    'name' => $driver->name,
                    'mobile' => $driver->mobile,
                    'vehicle' => $assignment->vehicle,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            Log::error('Driver login error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Driver logged out successfully'
        ]);
    }
}
