<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeValue extends Model
{
    protected $fillable = [
        'product_id',
        'product_attribute_id',
        'value',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get the product that owns this attribute value.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product attribute that this value belongs to.
     */
    public function productAttribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    /**
     * Get the formatted value for display
     */
    public function getDisplayValueAttribute(): string
    {
        if (empty($this->value)) {
            return '';
        }

        $attribute = $this->productAttribute;
        
        switch ($attribute->type) {
            case 'boolean':
                return $this->value ? 'Yes' : 'No';
            case 'date':
                try {
                    return \Carbon\Carbon::parse($this->value)->format('M d, Y');
                } catch (\Exception $e) {
                    return $this->value;
                }
            case 'multiselect':
            case 'checkbox':
                // Handle array values
                $values = is_array($this->value) ? $this->value : json_decode($this->value, true);
                return is_array($values) ? implode(', ', $values) : $this->value;
            default:
                return $this->value;
        }
    }

    /**
     * Get parsed value for multiselect/checkbox attributes
     */
    public function getParsedValueAttribute()
    {
        if (in_array($this->productAttribute->type, ['multiselect', 'checkbox'])) {
            return is_array($this->value) ? $this->value : json_decode($this->value, true);
        }
        
        return $this->value;
    }
}