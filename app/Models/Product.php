<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'brand',
        'price',
        'stock',
        'discount',
        'is_active',
        'is_new',
        'category_id',
        'image_url',
        'images',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'description' => 'array',
            'images' => 'array',
            'price' => 'decimal:2',
            'stock' => 'integer',
            'discount' => 'decimal:2',
            'is_active' => 'boolean',
            'is_new' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

 
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function specs()
    {
        return $this->hasMany(ProductSpec::class);
    }
}
