<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $table = 'cart';
    
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }

    public function getTaxAmountAttribute(): float
    {
        return $this->subtotal * 0.08; // 8% tax rate
    }

    public function getTotalAttribute(): float
    {
        return $this->subtotal + $this->tax_amount;
    }

    public function getItemCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public static function findOrCreateForUser($userId = null, $sessionId = null): self
    {
        $query = static::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        $cart = $query->first();
        
        if (!$cart) {
            $cart = static::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);
        }
        
        return $cart;
    }

    public function addItem(ProductVariation $variation, int $quantity = 1): CartItem
    {
        $existingItem = $this->items()
            ->where('product_variation_id', $variation->id)
            ->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity,
            ]);
            return $existingItem;
        }

        return $this->items()->create([
            'product_variation_id' => $variation->id,
            'quantity' => $quantity,
            'unit_price' => $variation->price,
        ]);
    }

    public function updateItemQuantity(ProductVariation $variation, int $quantity): ?CartItem
    {
        $item = $this->items()
            ->where('product_variation_id', $variation->id)
            ->first();

        if (!$item) {
            return null;
        }

        if ($quantity <= 0) {
            $item->delete();
            return null;
        }

        $item->update(['quantity' => $quantity]);
        return $item;
    }

    public function removeItem(ProductVariation $variation): bool
    {
        return $this->items()
            ->where('product_variation_id', $variation->id)
            ->delete() > 0;
    }

    public function clear(): void
    {
        $this->items()->delete();
    }
}
