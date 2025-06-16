<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'total_amount',
        'billing_address',
        'shipping_address',
        'payment_method',
        'payment_status',
        'payment_transaction_id',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function getLatestPaymentAttribute(): ?Payment
    {
        return $this->payments()->latest()->first();
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    public function canBeShipped(): bool
    {
        return $this->status === 'processing' && $this->payment_status === 'completed';
    }

    public static function createFromCart(Cart $cart, array $shippingAddress, array $billingAddress): self
    {
        $subtotal = $cart->subtotal;
        $taxAmount = $cart->tax_amount;
        $shippingCost = 0; // Calculate shipping cost here
        
        $order = static::create([
            'user_id' => $cart->user_id,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_cost' => $shippingCost,
            'total_amount' => $subtotal + $taxAmount + $shippingCost,
            'billing_address' => $billingAddress,
            'shipping_address' => $shippingAddress,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Create order items from cart items
        foreach ($cart->items as $cartItem) {
            $order->items()->create([
                'product_variation_id' => $cartItem->product_variation_id,
                'seller_id' => $cartItem->productVariation->product->seller_id,
                'product_name' => $cartItem->productVariation->product->name,
                'product_sku' => $cartItem->productVariation->sku,
                'product_attributes' => $cartItem->productVariation->attributes,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'total_price' => $cartItem->total_price,
                'commission_rate' => config('services.marketplace.commission_rate'),
                'commission_amount' => $cartItem->total_price * (config('services.marketplace.commission_rate') / 100),
                'status' => 'pending',
            ]);
        }

        return $order;
    }
}
