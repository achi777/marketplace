<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttribute extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'type',
        'options',
        'is_required',
        'is_filterable',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    public function getFormattedOptionsAttribute(): array
    {
        if (!$this->options) {
            return [];
        }
        
        if ($this->type === 'select' || $this->type === 'multiselect' || $this->type === 'radio' || $this->type === 'checkbox') {
            return is_array($this->options) ? $this->options : [$this->options];
        }
        
        return [];
    }

    public function validateValue($value): bool
    {
        if ($this->is_required && empty($value)) {
            return false;
        }
        
        switch ($this->type) {
            case 'text':
            case 'textarea':
                return is_string($value);
            case 'number':
                return is_numeric($value);
            case 'select':
            case 'radio':
                return in_array($value, $this->formatted_options);
            case 'multiselect':
            case 'checkbox':
                if (!is_array($value)) {
                    return false;
                }
                return empty(array_diff($value, $this->formatted_options));
            case 'boolean':
                return is_bool($value) || in_array($value, [0, 1, '0', '1', true, false]);
            default:
                return true;
        }
    }
}