<?php

namespace App\Controllers;

class LogoutController
{
  public function logout()
  {
    // Unset the cookie
    setcookie('uniqueKey', '', time() - 3600); // expire the cookie
    setcookie('username', '', time() - 3600); // expire the cookie

    // Unset the session variable
    unset($_SESSION['uniqueKey']);
    unset($_SESSION['username']);

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header('Location: /login');
    exit;
  }

}