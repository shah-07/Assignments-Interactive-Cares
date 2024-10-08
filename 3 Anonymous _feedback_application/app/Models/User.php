<?php

namespace App\Models;
use App\Storage\Storage;
class User
{
  private $userDataFile;

  public function __construct()
  {
    $this->userDataFile = (new Storage())->userDataFile;
  }

  public function register($username, $email, $password, $confirm_password)
  {
    // Validate the form data
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
      return array('error' => 'Please fill in all fields'); // Return error message if any field is empty
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
      return array('error' => 'Passwords do not match'); // Return error message if passwords don't match
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return array('error' => 'Invalid email'); // Return error message if email is invalid
    }

    // Check if username or email already exists
    $existingUserData = array();
    if (file_exists($this->userDataFile)) {
      $existingUserData = json_decode(file_get_contents($this->userDataFile), true);
    }
    foreach ($existingUserData as $user) {
      if ($user['username'] === $username || $user['email'] === $email) {
        return array('error' => 'Username or email already exists'); // Return error message if username or email already exists
      }
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate a unique key
    $uniqueKey = substr(md5(uniqid(rand(), true)), 0, 7);

    // Create a user data array
    $userData = array(
      'uniqueKey' => $uniqueKey,
      'username' => $username,
      'email' => $email,
      'password' => $hashed_password
    );

    // Add the new user data to the existing data
    $existingUserData[] = $userData;

    // Write the updated user data to the file
    file_put_contents($this->userDataFile, json_encode($existingUserData));

    return array('success' => true, 'uniqueKey' => $uniqueKey);
  }


  public function authenticate($email, $password)
  {
    // Check if the username and password are empty
    if (empty($email) || empty($password)) {
      return array('error' => 'Please enter your username and password'); // Return error message if either field is empty
    }

    // Read the user data from the file
    $existingUserData = array();
    if (file_exists($this->userDataFile)) {
      $existingUserData = json_decode(file_get_contents($this->userDataFile), true);
    }

    // Iterate through the user data to find a match
    foreach ($existingUserData as $user) {
      if ($user['email'] === $email) {
        // Check if the password matches using password_verify
        if (password_verify($password, $user['password'])) {
          return array('success' => true, 'uniqueKey' => $user['uniqueKey'], 'username' => $user['username']);
        } else {
          return array('error' => 'Invalid password'); // Return error message if password doesn't match
        }
      }
    }

    // If no match is found, return an error message
    return array('error' => 'Username not found');
  }
}