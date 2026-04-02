<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'role',
        'avatar',
        'address',
        'state',
        'is_active',
        'popular_bus_stop_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'role'              => 'string',
        'is_active'         => 'boolean',
    ];

    // ────────────────────────────────────────────────
    //  RELATIONSHIPS
    // ────────────────────────────────────────────────

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id');
    }

    /**
     * Get or create Customer Wallet (Main method for customers)
     */
    public function getCustomerWallet(): Wallet
    {
        return $this->wallet()->firstOrCreate(
            [
                'user_id'     => $this->id,
                'wallet_type' => 'customer'
            ],
            [
                'balance'      => 0.00,
                'total_earned' => 0.00,
            ]
        );
    }

    /**
     * Get or create Vendor/Main wallet (for backward compatibility)
     */
    public function getWallet(): Wallet
    {
        return $this->wallet()->firstOrCreate(
            ['user_id' => $this->id],
            [
                'wallet_type'  => 'main',
                'balance'      => 0.00,
                'total_earned' => 0.00,
            ]
        );
    }

    /**
     * All transactions belonging to this user
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    // Profile relationships
    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function vendorProfile()
    {
        return $this->hasOne(VendorProfile::class);
    }

    public function dispatcherProfile()
    {
        return $this->hasOne(DispatcherProfile::class);
    }

    public function popularBusStop()
    {
        return $this->belongsTo(PopularBusStop::class, 'popular_bus_stop_id');
    }

    public function profile()
    {
        return match ($this->role) {
            'customer'   => $this->customerProfile,
            'vendor'     => $this->vendorProfile,
            'dispatcher' => $this->dispatcherProfile,
            default      => null,
        };
    }

    // ────────────────────────────────────────────────
    //  HELPER METHODS
    // ────────────────────────────────────────────────

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor';
    }

    public function isDispatcher(): bool
    {
        return $this->role === 'dispatcher';
    }

    /**
     * Check if customer has sufficient balance in their customer wallet
     */
    public function hasSufficientWalletBalance(float $amount): bool
    {
        if (!$this->isCustomer()) {
            return false;
        }

        $wallet = $this->getCustomerWallet();
        return $wallet->balance >= $amount;
    }

    /**
     * Optional: Keep a simple deduct method for future use (outside orders)
     * But we are NOT using it for orders anymore
     */
    public function deductFromWallet(float $amount, string $description, $transactable = null): bool
    {
        if (!$this->isCustomer() || !$this->hasSufficientWalletBalance($amount)) {
            return false;
        }

        $wallet = $this->getCustomerWallet();

        return DB::transaction(function () use ($wallet, $amount, $description, $transactable) {
            $wallet->decrement('balance', $amount);

            $this->transactions()->create([
                'amount'            => $amount,
                'type'              => 'debit',
                'description'       => $description,
                'transactable_type' => $transactable ? get_class($transactable) : null,
                'transactable_id'   => $transactable?->id,
            ]);

            return true;
        });
    }
}