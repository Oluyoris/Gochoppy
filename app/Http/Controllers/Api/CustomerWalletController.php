<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerWalletController extends Controller
{
    /**
     * Get customer's wallet balance and basic info
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isCustomer()) {
            return response()->json([
                'success' => false,
                'message' => 'Only customers can access wallet'
            ], 403);
        }

        $wallet = $user->getCustomerWallet();

        return response()->json([
            'success' => true,
            'data' => [
                'balance'       => (float) $wallet->balance,
                'total_earned'  => (float) $wallet->total_earned,
                'wallet_type'   => 'customer',
            ]
        ]);
    }

    /**
     * Request to fund wallet (Manual Bank Transfer)
     */
    public function fund(Request $request)
    {
        $user = $request->user();

        if (!$user->isCustomer()) {
            return response()->json([
                'success' => false,
                'message' => 'Only customers can fund wallet'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'amount'        => 'required|numeric|min:100',
            'payment_method'=> 'required|in:bank_transfer,paystack',
            'proof'         => 'required_if:payment_method,bank_transfer|string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $amount = (float) $request->amount;

        if ($request->payment_method === 'paystack') {
            return response()->json([
                'success' => false,
                'message' => 'Paystack funding coming soon'
            ], 501);
        }

        // Manual Bank Transfer
        $deposit = Deposit::create([
            'user_id'        => $user->id,
            'amount'         => $amount,
            'payment_method' => 'bank_transfer',
            'status'         => 'pending',
            'proof'          => $request->proof,
            'reference'      => 'DEP-' . Str::upper(Str::random(10)),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Deposit request submitted successfully. Awaiting admin approval.',
            'data'    => [
                'deposit_id' => $deposit->id,
                'amount'     => $amount,
                'reference'  => $deposit->reference,
                'status'     => 'pending'
            ]
        ], 201);
    }

    /**
     * Get wallet transaction history (credits & debits)
     */
    public function transactions(Request $request)
    {
        $user = $request->user();

        if (!$user->isCustomer()) {
            return response()->json([
                'success' => false,
                'message' => 'Only customers can view wallet history'
            ], 403);
        }

        $transactions = $user->transactions()
            ->where(function ($query) {
                // More reliable filters
                $query->where('description', 'like', '%wallet%')
                      ->orWhere('description', 'like', '%deposit%')
                      ->orWhere('description', 'like', '%funded%')           // Added
                      ->orWhere('description', 'like', '%top up%')           // Added
                      ->orWhere('description', 'like', '%Payment for Order%')
                      ->orWhere('transactable_type', 'App\\Models\\Deposit'); // Most important fix
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page'    => $transactions->lastPage(),
                'per_page'     => $transactions->perPage(),
                'total'        => $transactions->total(),
            ]
        ]);
    }
}