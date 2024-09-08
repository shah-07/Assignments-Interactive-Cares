<?php

namespace App\Controllers;

use App\Models\User;

class RegisterController
{
  public function register()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $confirm_password = $_POST['confirm_password'];

      // Create a new User object
      $user = new User();

      // Call the register function on the User object
      $result = $user->register($name, $email, $password, $confirm_password);

      // Handle the result
      if (isset($result['error'])) {
        $_SESSION['error'] = $result['error'];
        $_SESSION['user_data'] = array('name' => $name, 'email' => $email, 'password' => $password, 'confirm_password' => $confirm_password);
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
      require_once __DIR__ . '/../Views/register.php';
    }
  }
}