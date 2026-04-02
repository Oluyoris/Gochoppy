<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DepositController extends Controller
{
    /**
     * List all pending customer deposits
     */
    public function index()
    {
        $deposits = Deposit::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.deposits.index', compact('deposits'));
    }

    /**
     * Show single deposit details (Proof + Info)
     */
    public function show(Deposit $deposit)
    {
        $deposit->load('user', 'approvedBy');

        return view('admin.deposits.show', compact('deposit'));
    }

    /**
     * Approve a deposit and credit customer wallet
     */
    public function approve(Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return redirect()->back()->with('error', 'Deposit already processed.');
        }

        DB::transaction(function () use ($deposit) {

            $deposit->update([
                'status'       => 'approved',
                'approved_by'  => auth('admin')->id(),
                'approved_at'  => now(),
            ]);

            // Credit customer's wallet
            $wallet = $deposit->user->getCustomerWallet();
            $wallet->increment('balance', $deposit->amount);

            // Record transaction
            $deposit->user->transactions()->create([
                'amount'            => $deposit->amount,
                'type'              => 'credit',
                'description'       => 'Wallet funded via Bank Transfer - Ref: ' . $deposit->reference,
                'transactable_type' => Deposit::class,
                'transactable_id'   => $deposit->id,
            ]);
        });

        return redirect()->route('admin.deposits.index')
                         ->with('success', 'Deposit approved and wallet credited successfully!');
    }

    /**
     * Reject a deposit
     */
    public function reject(Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return redirect()->back()->with('error', 'Deposit already processed.');
        }

        $deposit->update([
            'status'       => 'rejected',
            'approved_by'  => auth('admin')->id(),
            'approved_at'  => now(),
        ]);

        return redirect()->route('admin.deposits.index')
                         ->with('success', 'Deposit rejected.');
    }
}