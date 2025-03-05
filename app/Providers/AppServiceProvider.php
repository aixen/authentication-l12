<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthenticationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepository::class, function ($app) {
            return new UserRepository($app->make(User::class));
        });

        $this->app->singleton(AuthenticationService::class, function ($app) {
            return new AuthenticationService($app->make(UserRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
