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

  public function register($username, $email, $password): array
  {
    // Validate the form data
    if (empty($username) || empty($email) || empty($password)) {
      return array('error' => 'Please fill in all fields'); // Return error message if any field is empty
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return array('error' => 'Invalid email'); // Return error message if email is invalid
    }

    // Initialize an empty array for existing user data
    $existingUserData = [];

    // Check if the user data file exists and load the data
    if (file_exists($this->userDataFile)) {
      $existingUserData = json_decode(file_get_contents($this->userDataFile), true);

      // Check if the data is null (file is empty or JSON is invalid)
      if ($existingUserData === null) {
        $existingUserData = [];
      }
    }

    // check if the email already exists
    if (isset($existingUserData[$email])) {
      return ['error' => 'Email already exists']; // Return error if email already exists
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Create a user data array
    $userData = array(
      'username' => $username,
      'email' => $email,
      'password' => $hashed_password,
      'balance' => 0
    );

    // Add the new user data to the existing data
    $existingUserData[$email] = $userData;

    // Write the updated user data to the file
    file_put_contents($this->userDataFile, json_encode($existingUserData));

    return array('success' => true);
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
    if (isset($existingUserData[$email])) {
      $user = $existingUserData[$email];

      $additionalData = [
        'email' => $user['email'],
        'username' => $user['username'],
        'balance' => $user['balance']
      ];

      // Check if the password matches using password_verify
      if (password_verify($password, $user['password'])) {
        return array_merge(['success' => true], $additionalData);
      } else {
        return array('error' => 'Invalid password'); // Return error message if password doesn't match
      }
    }

    // If no match is found, return an error message
    return array('error' => 'Username not found');
  }
}