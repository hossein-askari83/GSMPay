<?php

namespace App\Providers;

use App\Domains\File\Repositories\FileRepository;
use App\Domains\File\Interfaces\FileRepositoryInterface;
use App\Domains\Post\Interfaces\PostRepositoryInterface;
use App\Domains\Post\Repositories\ElasticsearchPostRepository;
use App\Domains\Post\Repositories\EloquentPostRepository;
use App\Domains\User\Repositories\UserRepository;
use App\Domains\User\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(PostRepositoryInterface::class, ElasticsearchPostRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
