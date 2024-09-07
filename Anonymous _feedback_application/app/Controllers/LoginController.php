<?php

namespace App\Controllers;

use App\Models\User;

class LoginController
{
  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $user = new User();
      if ($user->authenticate($username, $password)) {
        $_SESSION['username'] = $username;
        header('Location: dashboard');
      } else {
        echo 'Invalid username or password';
      }

    } else {
      require_once __DIR__ . '/../Views/login.php';
    }
  }
}