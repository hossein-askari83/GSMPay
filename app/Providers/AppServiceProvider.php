<?php

namespace App\Providers;

use App\Domains\File\Repositories\FileRepository;
use App\Domains\File\Repositories\FileRepositoryInterface;
use App\Domains\User\Repositories\UserRepository;
use App\Domains\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
