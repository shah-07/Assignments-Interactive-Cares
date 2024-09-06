<?php

namespace App\Controllers;

use App\Models\User;
class RegisterController
{
  public function register()
  {
    // Create a new User object
    $user = new User();

    // Get the form data from the request
    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Call the register function on the User object
    $result = $user->register($username, $email, $password, $confirm_password);

    // Handle the result
    if ($result) {
      // Redirect to login page
      $url = dirname(__FILE__) . '/../views/login.php';
      header('Location: ' . $url);
      exit;
    } else {
      // Display error message
      $error = 'Registration failed';
      // You can also redirect back to the registration form with the error message
    }
  }
}