<?php

use App\Controllers\Customer\Dashboard;
use App\Controllers\Customer\Deposit;
use App\Controllers\Customer\Transfer;
use App\Controllers\Customer\Withdraw;
use App\Routes\Router;
use App\Services\AuthService;
use App\Controllers\AdminController;
use App\Controllers\CustomerController;
use App\Services\Session;

session_start();
date_default_timezone_set('Asia/Dhaka');

require '../vendor/autoload.php';

const BASE_PATH = __DIR__ . '/../';

require BASE_PATH . 'core/functions.php';

// Create a Router instance
$router = new Router();

// Create a single instance of AuthService
$authService = new AuthService();



// Define Routes
$router->add('/', fn() => include '../views/welcome.php');

$router->add('/login', fn() => $authService::login());

$router->add('/register', fn() => $authService::register());

//customer routes
$router->add('/dashboard', fn() => requireAuth(function () {
    $dashboard = new Dashboard;
    $dashboard->view();
}));
$router->add('/deposit', fn() => requireAuth(function () {
    $deposit = new Deposit;
    $deposit->view();
}));
$router->add('/transfer', fn() => requireAuth(function () {
    $transfer = new Transfer;
    $transfer->view();
}));
$router->add('/withdraw', fn() => requireAuth(function () {
    $withdraw = new Withdraw;
    $withdraw->view();
}));


//user logout
$router->add('/logout', fn() => $authService::logout());

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
