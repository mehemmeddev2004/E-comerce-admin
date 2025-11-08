<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpec extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'key',
        'name',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Əlaqə: əsas product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Əlaqə: spec values
    public function values()
    {
        return $this->hasMany(ProductSpecValue::class);
    }
}
