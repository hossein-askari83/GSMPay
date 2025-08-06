<?php

namespace App\Domains\Post\Models;

use App\Domains\User\Models\User;
use App\Domains\View\Interfaces\ViewableInterface;
use App\Domains\View\Traits\HasViews;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model implements ViewableInterface
{
    use HasFactory, HasViews;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'views',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    protected static function newFactory()
    {
        return PostFactory::new();
    }

    public function getViewableKey(): int
    {
        return $this->id;
    }
}