<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
        $this->mapInternalAPI();

        if (config('app.debug')) {
            $this->mapDevRoutes();
        }
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace'  => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => ['api', 'auth:api'],
            'namespace'  => $this->namespace,
            'prefix'     => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }


    private function mapDevRoutes()
    {
        /** @noinspection PhpUnusedParameterInspection */
        Route::group([
            'namespace' => $this->namespace . '\Dev',
            'prefix'    => 'dev',
            'middleware' => 'web',
        ], function ($router) {
            require base_path('routes/dev.php');
        });
    }

    private function mapInternalAPI()
    {
        /** @noinspection PhpUnusedParameterInspection */
        Route::group([
            'namespace' => $this->namespace . '\InternalApi',
            'prefix'    => 'internal-api',
            'middleware' => ['web'],
        ], function ($router) {
            require base_path('routes/internal-api.php');
        });
    }
}
