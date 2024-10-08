<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
  public static function register(): void
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = $_POST['password'];

      // Create a new User object
      $user = new User();

      // Call the register function on the User object
      $result = $user->register($name, $email, $password);

      // Handle the result
      if (isset($result['error'])) {
        $_SESSION['error'] = $result['error'];
        $_SESSION['user_data'] = array('name' => $name, 'email' => $email, 'password' => $password);
        header('Location: /register');
        exit;
      } elseif (isset($result['success'])) {
        // Redirect to login page
        header('Location: /login');
        exit;
      } else {
        // Handle unexpected result
        $error = 'Unexpected error occurred';
      }
    } else {
      include '../views/register.php';
    }
  }

  public static function login(): void
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
        $_SESSION['email'] = $result['email'];

        // Set a cookie to store the unique key
        setcookie('email', $result['email'], time() + 3600, "/");
        setcookie('username', $result['username'], time() + 3600, "/");

        // Redirect to dashboard or profile page
        header('Location: /dashboard');
        exit;
      } else {
        // Handle unexpected result
        $error = 'Unexpected error occurred';
      }

    } else {
      include '../views/login.php';
    }
  }

  public static function logout()
  {

    // Destroy the session
    Session::destroy();

    // Redirect to the login page
    header('Location: /login');
    exit;
  }
}
