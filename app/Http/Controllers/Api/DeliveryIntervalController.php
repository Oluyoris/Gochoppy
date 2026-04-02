<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PopularBusStop;
use App\Models\DeliveryInterval;
use App\Models\User;
use Illuminate\Http\Request;

class DeliveryIntervalController extends Controller
{
    /**
     * Get Delivery Fee + Time between Vendor Bus Stop and User's Selected Bus Stop
     * 
     * This will be called from React Native when user selects a bus stop on checkout
     */
    public function getDeliveryFee(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'vendor_id'        => 'required|exists:users,id',
            'user_bus_stop_id' => 'required|exists:popular_bus_stops,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Get Vendor and their assigned Popular Bus Stop
        $vendor = User::where('id', $request->vendor_id)
                      ->where('role', 'vendor')
                      ->with('vendorProfile.popularBusStop')
                      ->first();

        if (!$vendor || !$vendor->vendorProfile || !$vendor->vendorProfile->popularBusStop) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor does not have a Popular Bus Stop assigned. Please contact admin.'
            ], 400);
        }

        $vendorBusStopId = $vendor->vendorProfile->popular_bus_stop_id;

        // Find interval (check both directions)
        $interval = DeliveryInterval::where(function ($query) use ($vendorBusStopId, $request) {
                $query->where('from_stop_id', $vendorBusStopId)
                      ->where('to_stop_id', $request->user_bus_stop_id);
            })
            ->orWhere(function ($query) use ($vendorBusStopId, $request) {
                $query->where('from_stop_id', $request->user_bus_stop_id)
                      ->where('to_stop_id', $vendorBusStopId);
            })
            ->first();

        if (!$interval) {
            return response()->json([
                'success' => false,
                'message' => 'No delivery route found between these bus stops.'
            ], 404);
        }

        return response()->json([
            'success'          => true,
            'delivery_fee'     => (int) $interval->price,
            'estimated_time'   => $interval->estimated_time,
            'vendor_bus_stop'  => $vendor->vendorProfile->popularBusStop->name,
            'user_bus_stop'    => $interval->fromStop->id === $vendorBusStopId 
                                  ? $interval->toStop->name 
                                  : $interval->fromStop->name,
        ]);
    }

    /**
     * Optional: Get all bus stops (already have public route, but we can keep here too)
     */
    public function index()
    {
        $stops = PopularBusStop::orderBy('name')->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data'    => $stops
        ]);
    }
}