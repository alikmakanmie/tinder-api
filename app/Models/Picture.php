<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_url',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
