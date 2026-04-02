<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminDeliverySetting;
use Illuminate\Http\Request;

class AdminDeliverySettingController extends Controller
{
    public function index()
    {
        $settings = AdminDeliverySetting::firstOrCreate(
            ['is_active' => true],
            [
                'dispatch_percentage' => 60,
                'admin_percentage'    => 40,
            ]
        );

        // Pass both bus stops and settings to the same blade
        $busStops = \App\Models\PopularBusStop::orderBy('name')->get();

        return view('admin.bus-stops.index', compact('busStops', 'settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'dispatch_percentage' => 'required|integer|min:0|max:100',
        ]);

        $dispatchPercent = (int) $request->dispatch_percentage;
        $adminPercent    = 100 - $dispatchPercent;

        $settings = AdminDeliverySetting::where('is_active', true)->first();

        if (!$settings) {
            $settings = AdminDeliverySetting::create(['is_active' => true]);
        }

        $settings->update([
            'dispatch_percentage' => $dispatchPercent,
            'admin_percentage'    => $adminPercent,
        ]);

        return redirect()->route('admin.delivery-settings.index')
                         ->with('success', "Delivery split updated! Dispatch: {$dispatchPercent}% | Admin: {$adminPercent}%");
    }
}