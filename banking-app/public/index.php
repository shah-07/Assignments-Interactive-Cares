<?php
session_start();
require '../vendor/autoload.php';

use App\Routes\Router;
use App\Controllers\AdminController;
use App\Controllers\CustomerController;

// Create a Router instance
$router = new Router();

// Define Routes
$router->add('/login', function() {
    include '../views/login.php';
});

$router->add('/register', function() {
    include '../views/register.php';
});

// $router->add('/admin', function() {
//     if ($_SESSION['user']['role'] === 'Admin') {
//         $adminController = new AdminController();
//         $customers = $adminController->viewAllCustomers();
//         $transactions = $adminController->viewAllTransactions();
//         include '../views/admin_dashboard.php';
//     } else {
//         header('Location: /login');
//         exit();
//     }
// });

// $router->add('/customer', function() {
//     if ($_SESSION['user']['role'] === 'Customer') {
//         $customerController = new CustomerController();
//         $transactions = $customerController->viewTransactions($_SESSION['user']['email']);
//         $balance = $customerController->getBalance($_SESSION['user']['email']);
//         include '../views/customer_dashboard.php';
//     } else {
//         header('Location: /login');
//         exit();
//     }
// });

// Parse the requested URL
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Dispatch the route
$router->dispatch($url);


