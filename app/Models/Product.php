<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'sku',
        'images',
        'status',
        'rejection_reason',
        'is_featured',
        'weight',
        'dimensions',
    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'boolean',
        'weight' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(Str::random(8));
            }
        });
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, ProductVariation::class, 'product_id', 'product_variation_id');
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function getAttributeValuesGroupedAttribute(): array
    {
        $grouped = [];
        foreach ($this->attributeValues as $attributeValue) {
            $attribute = $attributeValue->productAttribute;
            $grouped[$attribute->name] = [
                'attribute' => $attribute,
                'value' => $attributeValue->display_value,
                'raw_value' => $attributeValue->value,
            ];
        }
        return $grouped;
    }

    public function getAttributeValue($attributeName): ?string
    {
        $attributeValue = $this->attributeValues()
            ->whereHas('productAttribute', function ($query) use ($attributeName) {
                $query->where('name', $attributeName);
            })
            ->first();
            
        return $attributeValue ? $attributeValue->display_value : null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeBySeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function getMainImageAttribute(): ?string
    {
        return $this->images[0] ?? null;
    }

    public function getMinPriceAttribute(): ?float
    {
        return $this->variations()->min('price');
    }

    public function getMaxPriceAttribute(): ?float
    {
        return $this->variations()->max('price');
    }

    public function getTotalStockAttribute(): int
    {
        return $this->variations()->sum('stock_quantity');
    }

    public function getInStockAttribute(): bool
    {
        return $this->total_stock > 0;
    }
}
