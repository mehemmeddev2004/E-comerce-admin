<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_spec_id',
        'key',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Əlaqə: əsas spec
    public function spec()
    {
        return $this->belongsTo(ProductSpec::class, 'product_spec_id');
    }
}
