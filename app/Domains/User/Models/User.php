<?php

namespace App\Domains\User\Models;

use App\Domains\File\Interfaces\FileOwnerInterface;
use App\Domains\File\Models\File;
use App\Domains\File\Traits\HasFiles;
use App\Domains\Post\Models\Post;
use App\Domains\View\Interfaces\ViewableInterface;
use App\Domains\View\Models\View;
use App\Domains\View\Traits\HasViews;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, FileOwnerInterface, ViewableInterface
{
    use HasFactory, Notifiable, HasFiles, HasViews;

    protected $fillable = [
        'name',
        'mobile',
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

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'model');
    }

    public function getFileKey(): int|string
    {
        return $this->id;
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

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }


    public function views(): HasManyThrough
    {
        return $this->hasManyThrough(
            related: View::class,
            through: Post::class,
            firstKey: 'user_id',
            secondKey: 'viewable_id',
            localKey: 'id',
            secondLocalKey: 'id'
        )
            ->where('viewable_type', Post::class);
    }


}
