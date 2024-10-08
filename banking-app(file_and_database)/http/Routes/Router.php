<?php

namespace App\Routes;

use App\Controllers\AdminController;
use App\Controllers\CustomerController;
use App\Services\TestService;

class Router
{
    private $routes = [];

    public function add($route, $action)
    {
        $this->routes[$route] = $action;
    }

    public function dispatch($url)
    {
        if (array_key_exists($url, $this->routes)) {
            return call_user_func($this->routes[$url]);
        } else {
            // If route not found, show 404
            http_response_code(404);
            // echo "404 - Page not found!";
            header("location: /");
        }
    }

    public function run()
    {

        $customerController = new CustomerController();
        $adminController = new AdminController();

        // guest Route
        $this->add('/', fn() => include '../views/welcome.php');



        //customer routes
        $this->add('/login', fn() => $customerController->login());
        $this->add('/register', fn() => $customerController->register());
        $this->add('/dashboard', fn() => requireAuth(function () use ($customerController) {
            $customerController->dashboard();
        }));
        $this->add('/deposit', fn() => requireAuth(function () use ($customerController) {
            $customerController->deposit();
        }));
        $this->add('/transfer', fn() => requireAuth(function () use ($customerController) {
            $customerController->transfer();
        }));
        $this->add('/withdraw', fn() => requireAuth(function () use ($customerController) {
            $customerController->withdraw();
        }));
        //user logout
        $this->add('/logout', fn() => $customerController->logout());


        //Admin routes
        $this->add('/admin/customers', fn() => $adminController->viewAllCustomers());
        $this->add('/admin/transactions', fn() => $adminController->viewAllTransactions());
        $this->add('/admin/customer_transactions', fn() => $adminController->viewCustomerTransactions());

    }
}
