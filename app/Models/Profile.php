<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'avatar',
        'bio',
        'phone',
        'address',
        'city',
        'country',
        'birth_date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
