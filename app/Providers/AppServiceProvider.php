<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Using Closure based composers...
        \View::composer('layouts.partials.sidebar_tenant', function ($view) {
            $view->withFlats(auth()->user()->registeredFlats);
        });

        // Using Closure based composers...
        \View::composer('layouts.partials.sidebar_manager', function ($view) {
            $view->withHouses(auth()->user()->company->houses);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
