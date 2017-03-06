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
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('index');
    }

    return view('index');


});
use Illuminate\Routing\Router;

Auth::routes();

$router->get('/forgot-password',
    ['as' => 'auth.forgot', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
//$router->post('/forgot-password', ['as'=>'auth.forgot.post', 'uses' => 'Auth\ForgotPasswordController@forgetHandler']);
$router->get('/new-company', ['as' => 'new-company', 'uses' => 'Auth\RegisterController@newCompany']);
$router->post('/new-company', ['as' => 'new-company', 'uses' => 'Auth\RegisterController@newCompanyHandle']);
$router->get('/new-company/{inn}', ['as' => 'new-company.search', 'uses' => 'Auth\RegisterController@inn']);


$router->group(['middleware' => 'auth'], function (Router $router) {
    $router->get('logout', 'Auth\LoginController@logout');
    // $router->get('/', ['as'=>'index', 'uses' => 'HomeController@index']);
    $router->get('/home', ['as' => 'index', 'uses' => 'HomeController@index']);
    $router->get('/profile', ['as' => 'profile', 'uses' => 'UserController@profile']);
    $router->patch('/profile', ['as' => 'profile.post', 'uses' => 'UserController@profile_post']);
    $router->get('houses/{house}/votings/{voting}/people',
        ['as' => 'houses.votings.peoples', 'uses' => 'VotingController@people']);
    $router->get('houses/{house}/votings/{voting}/solution',
        ['as' => 'houses.votings.solution', 'uses' => 'VotingController@solution']);
    $router->get('houses/{house}/votings/create',
        ['as' => 'houses.votings.create', 'uses' => 'VotingController@create']);
    $router->post('houses/{house}/votings', ['as' => 'houses.votings.store', 'uses' => 'VotingController@store']);
    $router->post('houses/load_streets', ['as' => 'houses.load_streets', 'uses' => 'HousesController@load_streets']);
    $router->get('houses/{house}/votings/{voting}', ['as' => 'houses.votings.show', 'uses' => 'VotingController@show']);
    $router->get('houses/{house}/votings/{voting}/download',
        ['as' => 'houses.votings.download', 'uses' => 'VotingController@download']);

    $router->resource('houses', 'HousesController');
    $router->get('houses/{house}/{flat}/download',
        ['as' => 'houses.flat.download', 'uses' => 'HousesController@download']);
    $router->get('houses/{house}/{flat}/active', ['as' => 'houses.flat.active', 'uses' => 'HousesController@active']);

    $router->get('flats/attach', ['as' => 'flats.attach', 'uses' => 'FlatController@attach']);
    $router->post('flats/attach', ['as' => 'flats.attach.post', 'uses' => 'FlatController@attachHandler']);
    $router->get('flats/{flat}', ['as' => 'flats.show', 'uses' => 'FlatController@show']);
    $router->get('flats/{flat}/edit', ['as' => 'flats.edit', 'uses' => 'FlatController@edit']);
    $router->post('flats/{flat}/update', ['as' => 'flats.update', 'uses' => 'FlatController@update']);
    $router->post('flats/{flat}/activate', ['as' => 'flats.activate', 'uses' => 'FlatController@activate']);

    $router->resource('employees', 'Employees');
    $router->get('notification/{notification}', ['as' => 'notifications.read', 'uses' => 'Notifications@read']);
    $router->get('/stub-avatar/{email}', ['as' => 'employees.stub-avatar', 'uses' => 'Employees@stubAvatar']);

    $router->get('dictionaries', ['as' => 'dictionary.index', 'uses' => 'Dictionaries@index']);
    $router->get('send_invite', ['as' => 'send_invite', 'uses' => 'HousesController@send_invite']);
    $router->post('dictionaries', ['as' => 'dictionary.save', 'uses' => 'Dictionaries@save']);


    $router->resource('/voting', 'VotingController');

    $router->get('/flat/{flat}/voting/{voting}', ['as' => 'flat.voting', 'uses' => 'VoteController@voting']);
    $router->post('/flat/{flat}/voting/{voting}/{votingItem}/{vote}',
        ['as' => 'flat.voting.vote', 'uses' => 'VoteController@vote']);
});
