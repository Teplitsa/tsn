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

Auth::routes();
$router->get('/forgot-password', ['as'=>'auth.forgot', 'uses' => 'Auth@forget']);
$router->post('/forgot-password', ['as'=>'auth.forgot.post', 'uses' => 'Auth@forgetHandler']);
$router->post('/esia', ['as'=>'esia.auth', 'uses' => 'Auth@esia']);



$router->group(['middleware'=>'auth'], function(Router $router){
    $router->get('logout', 'Auth\LoginController@logout');
    $router->get('/', ['as'=>'index', 'uses' => 'HomeController@index']);
    $router->get('/home', ['as'=>'home', 'uses' => 'HomeController@index']);

    $router->resource('employees', 'Employees');
    $router->get('notification/{notification}', ['as'=>'notifications.read', 'uses'=>'Notifications@read']);
    $router->get('/stub-avatar/{email}', ['as'=>'employees.stub-avatar', 'uses' => 'Employees@stubAvatar']);

    $router->get('dictionaries', ['as'=>'dictionary.index', 'uses'=>'Dictionaries@index']);
    $router->post('dictionaries', ['as'=>'dictionary.save', 'uses'=>'Dictionaries@save']);
});
