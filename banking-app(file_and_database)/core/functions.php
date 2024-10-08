<?php


function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

// Middleware function to check authentication
function requireAuth($callback)
{
    if (isset($_COOKIE['email'])) {
        $callback();
    } else {
        header('Location: /login');
        exit();
    }
}

function urlIs($value)
{
    return $_SERVER['REQUEST_URI'] === $value;
}
