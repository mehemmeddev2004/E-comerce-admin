<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Əlaqə: alt kateqoriyalar
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Əlaqə: əsas kateqoriya
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
