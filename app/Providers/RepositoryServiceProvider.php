<?php

namespace App\Providers;

use App\Repositories\Contracts\UserRepository;
use App\Repositories\Instances\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->bind(UserRepository::class, UserRepositoryEloquent::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
