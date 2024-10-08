<?php

namespace App\Controllers;

use App\Models\User;

class LoginController
{
  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = $_POST['email'];
      $password = $_POST['password'];
      $user = new User();
      // Handle the result
      $result = $user->authenticate($email, $password);
      if (isset($result['error'])) {
        $_SESSION['error'] = $result['error'];
        $_SESSION['user_data'] = array('email' => $email, 'password' => $password);
        header('Location: /login');
        exit;
      } elseif (isset($result['success'])) {
        // Set the unique key in the session
        $_SESSION['uniqueKey'] = $result['uniqueKey'];
        $_SESSION['username'] = $result['username'];

        // Set a cookie to store the unique key
        setcookie('uniqueKey', $result['uniqueKey'], time() + 3600);
        setcookie('username', $result['username'], time() + 3600);

        // Redirect to dashboard or profile page
        header('Location: /dashboard');
        exit;
      } else {
        // Handle unexpected result
        $error = 'Unexpected error occurred';
      }

    } else {
      require_once __DIR__ . '/../Views/login.php';
    }
  }
}