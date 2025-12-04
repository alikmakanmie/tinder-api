<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'location',
        'email',
    ];

    // Relationships
    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function swipes()
    {
        return $this->hasMany(Swipe::class);
    }

    public function receivedSwipes()
    {
        return $this->hasMany(Swipe::class, 'target_user_id');
    }
}
