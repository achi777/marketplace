<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerBalance extends Model
{
    protected $fillable = [
        'seller_id',
        'available_balance',
        'pending_balance',
        'total_earnings',
        'total_withdrawals',
        'commission_paid',
    ];

    protected $casts = [
        'available_balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'total_withdrawals' => 'decimal:2',
        'commission_paid' => 'decimal:2',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function getTotalBalanceAttribute(): float
    {
        return $this->available_balance + $this->pending_balance;
    }

    public function getNetEarningsAttribute(): float
    {
        return $this->total_earnings - $this->total_withdrawals;
    }

    public function canWithdraw(float $amount): bool
    {
        return $this->available_balance >= $amount && $amount > 0;
    }

    public function withdraw(float $amount): bool
    {
        if (!$this->canWithdraw($amount)) {
            return false;
        }

        $this->decrement('available_balance', $amount);
        $this->increment('total_withdrawals', $amount);

        return true;
    }

    public function releasePendingFunds(float $amount): void
    {
        $releaseAmount = min($amount, $this->pending_balance);
        
        $this->decrement('pending_balance', $releaseAmount);
        $this->increment('available_balance', $releaseAmount);
    }
}
