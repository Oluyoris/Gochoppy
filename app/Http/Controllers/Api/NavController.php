<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NavController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Common data for all roles
        $data = [
            'categories' => [
                [
                    'id' => 'kitchen',
                    'name' => 'Food & Kitchen',
                    'icon' => 'restaurant', // or your icon name
                ],
                [
                    'id' => 'supermarket',
                    'name' => 'Groceries & Supermarket',
                    'icon' => 'shopping_cart',
                ],
                [
                    'id' => 'pharmacy',
                    'name' => 'Drugs & Pharmacy',
                    'icon' => 'local_pharmacy',
                ],
            ],
            'profile_summary' => [
                'name' => $user->name,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'role' => $user->role,
            ],
            'activities_summary' => [
                'active_orders' => 0,
            ],
        ];

        // Role-specific data
        if ($user->role === 'customer') {
            $data['activities_summary']['active_orders'] = Order::where('customer_id', $user->id)
                                                                ->whereNotIn('status', ['delivered', 'cancelled'])
                                                                ->count();
        } elseif ($user->role === 'vendor') {
            $data['activities_summary']['active_orders'] = Order::where('vendor_id', $user->id)
                                                                ->whereNotIn('status', ['delivered', 'cancelled'])
                                                                ->count();
        } elseif ($user->role === 'dispatcher') {
            $data['activities_summary']['active_orders'] = Order::where('dispatcher_id', $user->id)
                                                                ->whereNotIn('status', ['delivered', 'cancelled'])
                                                                ->count();
        }

        return response()->json([
            'success' => true,
            'nav_data' => $data,
        ]);
    }
}