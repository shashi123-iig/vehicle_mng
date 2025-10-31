<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class UserAuthController extends Controller
{
    /**
     * Driver login using mobile number only
     */
    public function login(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'mobile' => ['required', 'string']
            ]);

            // Find active assignment for driver mobile
            $assignment = VehicleAssignment::where('driver_mobile', $validated['mobile'])
                ->where('active', true)
                ->with('vehicle')
                ->first();

            // Check if found
            if (!$assignment) {
                return response()->json([
                    'status' => false,
                    'message' => 'No active vehicle found for this mobile number.'
                ], 404);
            }

            // Generate a simple token (optional: Sanctum can also be used if you prefer)
            $token = base64_encode($validated['mobile'] . '|' . now());

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token,
                'driver' => [
                    'name' => $assignment->driver_name,
                    'mobile' => $assignment->driver_mobile,
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

    /**
     * Driver logout (dummy, since we’re not using Sanctum token)
     */
    public function logout(Request $request)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Driver logged out successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Driver logout error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to logout. Try again later.'
            ], 500);
        }
    }
}
