<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Response::macro('flydownload', function ($file, $name = "attachment", $mime = 'application/octet-stream') {
            $mode = 'attachment';

            $response =  (new Response($file, 200))
                ->header('Content-Type', $mime);

            $disposition = $response->headers->makeDisposition($mode, $name, 'attachment');
            return ( $response
                ->header('Content-Disposition', $disposition )
                ->header('Content-Transfer-Encoding', 'binary'))
                ;
        });

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
