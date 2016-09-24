<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Illuminate\Routing\Router;
/** @var Router $router */
Route::get('/', ['as'=>'index', function () {
    return view('welcome');
}]);

Auth::routes();

Route::get('/home', ['as'=>'home', 'uses' => 'HomeController@index']);

$router->group(['middleware'=>'auth'], function(Router $router){
    $router->resource('employees', 'Employees');
    $router->get('notification/{notification}', ['as'=>'notifications.read', 'uses'=>'Notifications@read']);
    $router->get('/stub-avatar/{email}', ['as'=>'employees.stub-avatar', 'uses' => 'Employees@stubAvatar']);

    $router->get('dictionaries', ['as'=>'dictionary.index', 'uses'=>'Dictionaries@index']);
    $router->post('dictionaries', ['as'=>'dictionary.save', 'uses'=>'Dictionaries@save']);
});
