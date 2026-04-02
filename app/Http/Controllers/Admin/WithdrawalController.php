<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of withdrawals
     */
    public function index()
    {
        $withdrawals = Withdrawal::with(['wallet.user'])
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(20);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    /**
     * Approve a withdrawal request
     */
    public function approve(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== Withdrawal::STATUS_PENDING) {
            return redirect()->back()->with('error', 'Withdrawal has already been processed.');
        }

        $wallet = $withdrawal->wallet;

        // Safety check
        if ($wallet->balance < $withdrawal->amount) {
            return redirect()->back()->with('error', 'Insufficient wallet balance to approve this withdrawal.');
        }

        // Deduct from wallet
        $wallet->decrement('balance', $withdrawal->amount);

        // Approve withdrawal
        $withdrawal->update([
            'status'       => Withdrawal::STATUS_APPROVED,
            'processed_at' => now(),
            'processed_by' => auth('admin')->id(),
        ]);

        // Record debit transaction for audit
        Transaction::create([
            'user_id'           => $wallet->user_id,
            'amount'            => $withdrawal->amount,   // positive amount, type = debit
            'type'              => 'debit',
            'description'       => 'Withdrawal approved - Ref: ' . $withdrawal->reference,
            'transactable_type' => Withdrawal::class,
            'transactable_id'   => $withdrawal->id,
        ]);

        return redirect()->route('admin.withdrawals.index')
                         ->with('success', 'Withdrawal approved successfully!');
    }

    /**
     * Reject a withdrawal request
     */
    public function reject(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== Withdrawal::STATUS_PENDING) {
            return redirect()->back()->with('error', 'Withdrawal has already been processed.');
        }

        $wallet = $withdrawal->wallet;

        // Refund to wallet
        $wallet->increment('balance', $withdrawal->amount);

        // Reject
        $withdrawal->update([
            'status'       => Withdrawal::STATUS_REJECTED,
            'processed_at' => now(),
            'processed_by' => auth('admin')->id(),
        ]);

        // Record refund transaction
        Transaction::create([
            'user_id'           => $wallet->user_id,
            'amount'            => $withdrawal->amount,
            'type'              => 'credit',
            'description'       => 'Withdrawal rejected & refunded - Ref: ' . $withdrawal->reference,
            'transactable_type' => Withdrawal::class,
            'transactable_id'   => $withdrawal->id,
        ]);

        return redirect()->route('admin.withdrawals.index')
                         ->with('success', 'Withdrawal rejected. Amount refunded to wallet.');
    }
}