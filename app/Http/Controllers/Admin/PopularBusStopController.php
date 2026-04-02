<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopularBusStop;
use App\Models\DeliveryInterval;
use App\Models\AdminDeliverySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PopularBusStopController extends Controller
{
    public function index()
    {
        $busStops = PopularBusStop::orderBy('name')->get();
        $settings = AdminDeliverySetting::getActive();

        return view('admin.bus-stops.index', compact('busStops', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:popular_bus_stops,name|max:100',
        ]);

        DB::beginTransaction();
        try {
            $newStop = PopularBusStop::create([
                'name' => strtoupper(trim($request->name)),
            ]);

            $existingStops = PopularBusStop::where('id', '!=', $newStop->id)->get();

            foreach ($existingStops as $existing) {
                // Create both directions (A → B and B → A)
                DeliveryInterval::create([
                    'from_stop_id'   => $newStop->id,
                    'to_stop_id'     => $existing->id,
                    'price'          => 1500,        // default price
                    'estimated_time' => '20-30 mins',
                ]);

                DeliveryInterval::create([
                    'from_stop_id'   => $existing->id,
                    'to_stop_id'     => $newStop->id,
                    'price'          => 1500,
                    'estimated_time' => '20-30 mins',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.bus-stops.index')
                             ->with('success', 'New bus stop added successfully with default intervals!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * FIXED: Show bus stop with all intervals (PostgreSQL compatible)
     */
    public function show(PopularBusStop $busStop)
    {
        $intervals = DeliveryInterval::where('from_stop_id', $busStop->id)
            ->with('toStop')                                   // Eager load the relationship
            ->join('popular_bus_stops as to_stop_table', 'delivery_intervals.to_stop_id', '=', 'to_stop_table.id')
            ->orderBy('to_stop_table.name', 'asc')
            ->select('delivery_intervals.*')                   // Prevent column conflict
            ->get();

        return view('admin.bus-stops.show', compact('busStop', 'intervals'));
    }

    public function update(Request $request, PopularBusStop $busStop)
    {
        $request->validate([
            'intervals' => 'required|array',
            'intervals.*.price' => 'required|integer|min:500',
            'intervals.*.estimated_time' => 'required|string|max:50',
        ]);

        foreach ($request->intervals as $id => $data) {
            DeliveryInterval::where('id', $id)
                ->where('from_stop_id', $busStop->id)
                ->update([
                    'price'          => $data['price'],
                    'estimated_time' => $data['estimated_time'],
                ]);
        }

        return redirect()->route('admin.bus-stops.index')
                         ->with('success', 'Delivery intervals updated successfully!');
    }

    public function destroy(PopularBusStop $busStop)
    {
        $busStop->delete();
        return redirect()->route('admin.bus-stops.index')
                         ->with('success', 'Bus stop deleted.');
    }
}