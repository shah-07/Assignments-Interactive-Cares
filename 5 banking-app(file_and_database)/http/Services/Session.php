<?php

namespace App\Services;

class Session
{
    public static function unflash()
    {
        unset($_SESSION['user']);
    }
    public static function flush()
    {
        $_SESSION = [];
    }
    public static function destroy()
    {
        static::flush();

        session_destroy();

        // Unset the cookie
        setcookie('email', '', time() - 3600); // expire the cookie
        setcookie('username', '', time() - 3600); // expire the cookie
    }
}