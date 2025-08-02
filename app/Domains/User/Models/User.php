<?php

namespace App\Domains\User\Models;

use App\Domains\Post\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
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

    /**
     * Get the identifier that will be stored in the JWT token.
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return an array with custom claims to be added to the JWT token.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
