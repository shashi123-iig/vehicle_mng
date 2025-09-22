<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FuelEntry;
use Illuminate\Http\Request;

class FuelEntryController extends Controller
{
    // Get all fuel entries with vehicle info
    public function index()
    {
        $entries = FuelEntry::with('vehicle')->get();
        return response()->json([
            'success' => true,
            'message' => 'Fuel entries fetched successfully',
            'data' => $entries
        ]);
    }

    // Add new fuel entry
    public function store(Request $request)
    {
        $entry = FuelEntry::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Fuel entry created successfully',
            'data' => $entry
        ], 201);
    }

    // Show single fuel entry
    public function show($id)
    {
        $entry = FuelEntry::with('vehicle')->findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Fuel entry details fetched successfully',
            'data' => $entry
        ]);
    }

    // Update fuel entry
    public function update(Request $request, $id)
    {
        $entry = FuelEntry::findOrFail($id);
        $entry->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Fuel entry updated successfully',
            'data' => $entry
        ]);
    }

    // Delete fuel entry
    public function destroy($id)
    {
        FuelEntry::destroy($id);
        return response()->json([
            'success' => true,
            'message' => 'Fuel entry deleted successfully'
        ]);
    }
}
