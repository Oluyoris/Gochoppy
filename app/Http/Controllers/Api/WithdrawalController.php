<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\OrderNotification;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends Controller
{
    /**
     * Request withdrawal from wallet (vendor or dispatcher only)
     */
    public function request(Request $request)
    {
        $user = $request->user();

        if (!in_array($user->role, ['vendor', 'dispatcher'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors and dispatchers can request withdrawals.',
            ], 403);
        }

        $wallet = $user->getWallet();

        if ($wallet->balance <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No balance available for withdrawal.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'amount'         => 'required|numeric|min:100|max:' . $wallet->balance,
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:20',
            'account_name'   => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $withdrawal = Withdrawal::create([
            'wallet_id'      => $wallet->id,
            'amount'         => $request->amount,
            'status'         => 'pending',
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'reference'      => 'WD-' . strtoupper(uniqid()),
        ]);

        // Deduct balance immediately
        $wallet->decrement('balance', $request->amount);

        // Notify user
        if ($user->email) {
            try {
                Mail::to($user->email)->queue(new OrderNotification(
                    'Withdrawal Request Submitted',
                    'Your withdrawal request of ₦' . number_format($request->amount, 2) . ' has been submitted and is pending approval.',
                    $user
                ));
            } catch (\Exception $e) {
                Log::error('Withdrawal request notification failed', ['user_id' => $user->id]);
            }
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Withdrawal request submitted successfully',
            'withdrawal'  => $withdrawal,
            'new_balance' => $wallet->fresh()->balance,
        ], 201);
    }

    /**
     * Approve a withdrawal request (admin only)
     */
    public function approve(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This withdrawal is not pending.',
            ], 400);
        }

        $withdrawal->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        $user = $withdrawal->wallet->user;

        if ($user && $user->email) {
            try {
                Mail::to($user->email)->queue(new OrderNotification(
                    'Withdrawal Approved',
                    'Your withdrawal of ₦' . number_format($withdrawal->amount, 2) . ' has been approved.',
                    $user
                ));
            } catch (\Exception $e) {
                Log::error('Withdrawal approved notification failed', ['user_id' => $user->id]);
            }
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Withdrawal approved successfully',
            'withdrawal' => $withdrawal->fresh(),
        ]);
    }

    /**
     * Reject a withdrawal request (admin only) + refund
     */
    public function reject(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This withdrawal is not pending.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $withdrawal->update([
            'status'           => 'rejected',
            'rejected_at'      => now(),
            'rejection_reason' => $request->reason ?? null,
        ]);

        $wallet = $withdrawal->wallet;
        $wallet->increment('balance', $withdrawal->amount);

        $user = $wallet->user;

        if ($user && $user->email) {
            try {
                Mail::to($user->email)->queue(new OrderNotification(
                    'Withdrawal Rejected',
                    'Your withdrawal request of ₦' . number_format($withdrawal->amount, 2) . ' has been rejected.' .
                    ($request->reason ? ' Reason: ' . $request->reason : '') .
                    ' The amount has been refunded to your wallet.',
                    $user
                ));
            } catch (\Exception $e) {
                Log::error('Withdrawal rejected notification failed', ['user_id' => $user->id]);
            }
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Withdrawal rejected and amount refunded',
            'withdrawal'  => $withdrawal->fresh(),
            'new_balance' => $wallet->fresh()->balance,
        ]);
    }

    /**
     * Get all withdrawal requests for the authenticated user
     */
    public function myRequests(Request $request)
    {
        $user = $request->user();

        if (!in_array($user->role, ['vendor', 'dispatcher'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors and dispatchers can view withdrawal requests.',
            ], 403);
        }

        // FIXED: Use wallet_id instead of user_id (since withdrawals table has wallet_id)
        $requests = Withdrawal::whereHas('wallet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success'  => true,
            'requests' => $requests,   // full paginator
        ]);
    }
}