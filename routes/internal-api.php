<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/** @var Router $router */
Route::get('user', ['uses'=>'User@me']);
Route::get('notifications', ['uses'=>'Notifications@load']);

$router->group(['prefix' => 'layout'], function (Router $router) {
    $router->get('', function () {
        abort(403);
    });
});

$router->get('/getavatar/{email?}', 'User@getAvatar');

