<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    /**
     * Fetch public system settings (fees, bank details, payment options)
     * No authentication required
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function settings()
    {
        $dbSettings = Setting::pluck('value', 'key')->toArray();

        $settings = [
            'delivery_fee'          => (float) ($dbSettings['delivery_fee'] ?? 500),
            'service_charge_amount' => (float) ($dbSettings['service_charge_amount'] ?? 200),
            'manual_bank_enabled'   => filter_var($dbSettings['manual_bank_enabled'] ?? '1', FILTER_VALIDATE_BOOLEAN),
            'manual_bank_name'      => $dbSettings['manual_bank_name'] ?? 'First Bank of Nigeria',
            'manual_account_number' => $dbSettings['manual_account_number'] ?? '1234567890',
            'manual_account_name'   => $dbSettings['manual_account_name'] ?? 'GoChoppy Limited',
            'paystack_enabled'      => filter_var($dbSettings['paystack_enabled'] ?? '1', FILTER_VALIDATE_BOOLEAN),
            'paystack_public_key'   => $dbSettings['paystack_public_key'] ?? '',
        ];

        return response()->json([
            'success'  => true,
            'settings' => $settings,
        ]);
    }

    /**
     * Home screen items with search, type filter, and pagination
     *
     * GET /api/home/items
     * Optional params: ?search=chicken &type=kitchen
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function homeItems(Request $request)
    {
        try {
            $query = Item::with('vendor.vendorProfile')
                         ->where('is_available', true);

            // Search by item name or vendor name
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhereHas('vendor', function ($v) use ($search) {
                          $v->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by vendor type (kitchen, supermarket, pharmacy)
            if ($request->filled('type') && in_array($request->type, ['kitchen', 'supermarket', 'pharmacy'])) {
                $query->where('vendor_type', $request->type);
            }

            // Paginate results
            $paginator = $query->latest()->paginate(20);

            // Transform items safely — NOW INCLUDING LOGO
            $transformedItems = $paginator->getCollection()->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'name'  => $item->name,
                    'price' => (float) $item->price,
                    'image' => $item->image ? asset('storage/' . $item->image) : null,
                    'vendor' => [
                        'id'   => $item->vendor->id ?? null,
                        'name' => $item->vendor->vendorProfile->company_name ?? $item->vendor->name ?? 'Unknown Vendor',
                        'type' => $item->vendor_type ?? null,
                        'logo' => $item->vendor->vendorProfile->logo 
                            ? asset('storage/' . $item->vendor->vendorProfile->logo) 
                            : null,  // ← THIS ADDS THE REAL LOGO URL
                    ],
                ];
            });

            return response()->json([
                'success' => true,
                'items'   => $transformedItems,
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                    'from'         => $paginator->firstItem(),
                    'to'           => $paginator->lastItem(),
                ],
                'filters' => [
                    'types'           => ['kitchen', 'supermarket', 'pharmacy'],
                    'applied_search'  => $request->search ?? null,
                    'applied_type'    => $request->type ?? null,
                ],
                'timestamp' => now()->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            Log::error('Home items error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch items. Please try again later.',
                'error'   => $e->getMessage(), // remove in production
            ], 500);
        }
    }
}