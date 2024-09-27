<?php

namespace App\Routes;

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
}
