<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Fetch all settings as key => value
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', [
            'serviceChargeAmount'  => $settings['service_charge_amount'] ?? 200,
            'deliveryFee'          => $settings['delivery_fee'] ?? 500,
            'referralBonus'        => $settings['referral_bonus'] ?? 200,
            'discountAmount'       => $settings['discount_amount'] ?? 100,

            'paystackPublicKey'    => $settings['paystack_public_key'] ?? '',
            'paystackSecretKey'    => $settings['paystack_secret_key'] ?? '',

            'manualBankName'       => $settings['manual_bank_name'] ?? '',
            'manualAccountNumber'  => $settings['manual_account_number'] ?? '',
            'manualAccountName'    => $settings['manual_account_name'] ?? '',

            // Convert to real boolean (handles '1', '0', null, etc.)
            'paystackEnabled'      => isset($settings['paystack_enabled']) && $settings['paystack_enabled'] != '0',
            'manualBankEnabled'    => isset($settings['manual_bank_enabled']) && $settings['manual_bank_enabled'] != '0',
        ]);
    }

    public function update(Request $request)
    {
        // Validation rules for each field
        $rules = [
            'service_charge_amount' => 'required|numeric|min:0',
            'delivery_fee'          => 'required|numeric|min:0',
            'referral_bonus'        => 'required|numeric|min:0',
            'discount_amount'       => 'required|numeric|min:0',

            'paystack_public_key'   => 'nullable|string|max:255',
            'paystack_secret_key'   => 'nullable|string|max:255',

            'manual_bank_name'      => 'nullable|string|max:100',
            'manual_account_number' => 'nullable|string|max:50',
            'manual_account_name'   => 'nullable|string|max:100',

            // Checkboxes: only accept '1' when checked (or absent = 0)
            'paystack_enabled'      => 'nullable|in:1',
            'manual_bank_enabled'   => 'nullable|in:1',
        ];

        $validated = $request->validate($rules);

        // Handle checkboxes manually (absent checkbox = 0)
        $paystackEnabled    = $request->has('paystack_enabled') ? 1 : 0;
        $manualBankEnabled  = $request->has('manual_bank_enabled') ? 1 : 0;

        // Prepare all data to save
        $settingsData = [
            'service_charge_amount' => $validated['service_charge_amount'],
            'delivery_fee'          => $validated['delivery_fee'],
            'referral_bonus'        => $validated['referral_bonus'],
            'discount_amount'       => $validated['discount_amount'],

            'paystack_enabled'      => $paystackEnabled,
            'paystack_public_key'   => $validated['paystack_public_key'] ?? '',
            'paystack_secret_key'   => $validated['paystack_secret_key'] ?? '',

            'manual_bank_enabled'   => $manualBankEnabled,
            'manual_bank_name'      => $validated['manual_bank_name'] ?? '',
            'manual_account_number' => $validated['manual_account_number'] ?? '',
            'manual_account_name'   => $validated['manual_account_name'] ?? '',
        ];

        // Save each setting using updateOrCreate
        foreach ($settingsData as $key => $value) {
            // Only save known/allowed keys (extra safety)
            if (in_array($key, Setting::KEYS)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'type'  => $this->determineType($value),
                    ]
                );
            }
        }

        return redirect()->route('admin.settings.index')
                         ->with('success', 'Settings updated successfully!');
    }

    /**
     * Helper to determine the type when saving
     */
    private function determineType($value): string
    {
        if (is_bool($value) || $value === 0 || $value === 1 || $value === '0' || $value === '1') {
            return 'boolean';
        }

        if (is_numeric($value)) {
            return 'decimal';
        }

        if (is_array($value) || is_object($value)) {
            return 'json';
        }

        return 'string';
    }
}