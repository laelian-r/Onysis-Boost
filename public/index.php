<?php

session_start();

require '../src/config/config.php';
require '../vendor/autoload.php';
require SRC . 'helper.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$router = new App\Router($_SERVER["REQUEST_URI"]);
$router->get('/', "DashboardController@index");
$router->get('/dashboard', "DashboardController@dashboard");
$router->get('/dashboard/delete/:id', "DashboardController@deleteRelease");

$router->get('/planning', "PlanningController@planning");
$router->get('/planning/:id', "PlanningController@viewPlanning");

$router->get('/new', "DashboardController@newRelease");

$router->post('/new', "DashboardController@newRelease");

$router->get('/login/', "UserController@showLogin");
$router->get('/register/', "UserController@showRegister");
$router->get('/logout/', "UserController@logout");

$router->post('/login/', "UserController@login");
$router->post('/register/', "UserController@register");

$router->get('/profil/:id', "UserController@showProfile");

$router->run();