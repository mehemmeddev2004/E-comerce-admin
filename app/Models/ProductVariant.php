<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'slug',
        'price',
        'stock',
        'discount',
        'images',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'discount' => 'decimal:2',
            'images' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Əlaqə: əsas product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Əlaqə: variant specs
    public function specs()
    {
        return $this->hasMany(ProductVariantSpec::class);
    }
}
