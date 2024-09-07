<?php
namespace App\Routes;

use App\Controllers\LoginController;
use App\Controllers\RegisterController;

//welcome page
if ($_SERVER['REQUEST_URI'] === '/') {
  require_once __DIR__ . '/../Views/welcome.php';
}

//Register route
if ($_SERVER['REQUEST_URI'] === '/register') {
  $registerController = new RegisterController();
  $registerController->register();
}

//Login route
if ($_SERVER['REQUEST_URI'] === '/login') {
  $loginController = new LoginController();
  $loginController->login();
}