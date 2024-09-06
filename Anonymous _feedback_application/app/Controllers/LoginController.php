<?php

namespace App\Controllers;

use App\Models\User;

class LoginController
{
  public function login()
  {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = new User();
    if ($user->authenticate($username, $password)) {
      $_SESSION['username'] = $username;
      header('Location: dashboard');
    } else {
      echo 'Invalid username or password';
    }
  }
}