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

/**
 * Service provider for binding repository interfaces to their implementations.
 *
 * Registers concrete implementations for repository interfaces, allowing for
 * dependency injection and easy swapping of repository implementations.
 *
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register repository bindings in the service container.
     *
     * Binds repository interfaces to their concrete implementations:
     * - UserRepositoryInterface -> UserRepository
     * - FileRepositoryInterface -> FileRepository
     * - PostRepositoryInterface -> ElasticsearchPostRepository|EloquentPostRepository
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(PostRepositoryInterface::class, ElasticsearchPostRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
