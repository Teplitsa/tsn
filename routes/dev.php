<?php

use Illuminate\Routing\Router;
/** @var Router $router */
$router->get('sign-in/{user}', 'Account@signIn');
$router->get('sign-up', 'Account@signUp');
$router->get('registered/{user}', 'Account@registered');