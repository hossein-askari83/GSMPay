<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'mobile',
        'password',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
    ];

    // Each user has many posts
    public function posts(): HasMany
    {
        return $this->hasMany(related: Post::class);
    }
}
