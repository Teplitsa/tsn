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
$router->get('/new-company', ['as'=>'new-company', 'uses' => 'Auth\RegisterController@newCompany']);
$router->post('/new-company', ['as'=>'new-company', 'uses' => 'Auth\RegisterController@newCompanyHandle']);
$router->get('/new-company/{inn}', ['as'=>'new-company.search', 'uses' => 'Auth\RegisterController@inn']);



$router->group(['middleware'=>'auth'], function(Router $router){
    $router->get('logout', 'Auth\LoginController@logout');
    $router->get('/', ['as'=>'index', 'uses' => 'HomeController@index']);
    $router->get('/home', ['as'=>'home', 'uses' => 'HomeController@index']);

    $router->resource('houses', 'HousesController');

    $router->get('flats/attach', ['as'=>'flats.attach', 'uses'=>'FlatController@attach']);
    $router->post('flats/attach', ['as'=>'flats.attach.post', 'uses'=>'FlatController@attachHandler']);
    $router->get('flats/{flat}', ['as'=>'flats.show', 'uses'=>'FlatController@show']);
    $router->post('flats/{flat}/activate', ['as'=>'flats.activate', 'uses'=>'FlatController@activate']);

    $router->resource('employees', 'Employees');
    $router->get('notification/{notification}', ['as'=>'notifications.read', 'uses'=>'Notifications@read']);
    $router->get('/stub-avatar/{email}', ['as'=>'employees.stub-avatar', 'uses' => 'Employees@stubAvatar']);

    $router->get('dictionaries', ['as'=>'dictionary.index', 'uses'=>'Dictionaries@index']);
    $router->post('dictionaries', ['as'=>'dictionary.save', 'uses'=>'Dictionaries@save']);
});
