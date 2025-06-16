<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_variation_id',
        'seller_id',
        'product_name',
        'product_sku',
        'product_attributes',
        'quantity',
        'unit_price',
        'total_price',
        'commission_rate',
        'commission_amount',
        'status',
    ];

    protected $casts = [
        'product_attributes' => 'array',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function productVariation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function getSellerAmountAttribute(): float
    {
        return $this->total_price - $this->commission_amount;
    }

    public function getAttributeDisplayAttribute(): string
    {
        if (!$this->product_attributes) {
            return '';
        }
        
        $display = [];
        foreach ($this->product_attributes as $key => $value) {
            $display[] = ucfirst($key) . ': ' . $value;
        }
        
        return implode(', ', $display);
    }
}
