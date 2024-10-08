<?php

use App\Routes\Router;

session_start();
date_default_timezone_set('Asia/Dhaka');

require '../vendor/autoload.php';


const BASE_PATH = __DIR__ . '/../';
require BASE_PATH . 'core/functions.php';



// Create a Router instance
$router = new Router();
$router->run();



// Parse the requested URL
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Dispatch the route
$router->dispatch($url);
