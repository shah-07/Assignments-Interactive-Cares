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
      if ($result) {
        // Redirect to login page
        header('Location: login.php');
        exit;
      } else {
        // Display error message
        $error = 'Registration failed';
        // You can also redirect back to the registration form with the error message
      }

    } else {
      require_once __DIR__ . '/../Views/register.php';
    }


  }
}