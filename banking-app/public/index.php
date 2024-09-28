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
