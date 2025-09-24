<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FuelEntry;
use Illuminate\Http\Request;

class FuelEntryController extends Controller
{
    public function index()
    {
        $entries = FuelEntry::with('vehicle')->get();
        return response()->json([
            'success' => true,
            'message' => 'Fuel entries fetched successfully',
            'data' => $entries
        ]);
    }

public function store(Request $request)
{
    $data = $request->all();

    if ($request->hasFile('image_vehicle_no')) {
        $filename = time().'_vehicle.'.$request->file('image_vehicle_no')->getClientOriginalExtension();
        $request->file('image_vehicle_no')->move(public_path('uploads/vehicle_no'), $filename);
        $data['image_vehicle_no'] = url('uploads/vehicle_no/'.$filename);
    }

    // Odometer image
    if ($request->hasFile('image_odometer')) {
        $filename = time().'_odometer.'.$request->file('image_odometer')->getClientOriginalExtension();
        $request->file('image_odometer')->move(public_path('uploads/odometer'), $filename);
        $data['image_odometer'] = url('uploads/odometer/'.$filename);
    }

    // Fuel meter image
    if ($request->hasFile('image_fuel_meter')) {
        $filename = time().'_fuel.'.$request->file('image_fuel_meter')->getClientOriginalExtension();
        $request->file('image_fuel_meter')->move(public_path('uploads/fuel_meter'), $filename);
        $data['image_fuel_meter'] = url('uploads/fuel_meter/'.$filename);
    }

    $entry = FuelEntry::create($data);

    return response()->json([
        'success' => true,
        'message' => 'Fuel entry created successfully',
        'data'    => $entry
    ], 201);
}


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
