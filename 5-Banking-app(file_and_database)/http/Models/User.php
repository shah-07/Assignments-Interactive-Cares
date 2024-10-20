<?php

namespace App\Models;
use App\Storage\Storage;
class User
{
  private $storage;

  public function __construct()
  {
    $this->storage = new Storage();
  }
  public function register($username, $email, $password): array
  {
    $storage = $this->storage;

    // Validate the form data
    if (empty($username) || empty($email) || empty($password)) {
      return array('error' => 'Please fill in all fields'); // Return error message if any field is empty
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return array('error' => 'Invalid email'); // Return error message if email is invalid
    }

    // Get the existing user data from both file and database
    $userDataFromStorage = $storage->getUserData();

    // Combine the user data from 'file' and 'database' keys
    $existingUserData = array_merge($userDataFromStorage['file'] ?? [], $userDataFromStorage['database'] ?? []);

    // Check if the email already exists in the merged data
    if (isset($existingUserData[$email])) {
      return ['error' => 'Email already exists']; // Return error if email already exists
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Create a user data array
    $newUserData = array(
      'username' => $username,
      'email' => $email,
      'password' => $hashed_password,
      'balance' => 0
    );

    // Save the new user data using saveUserData, it will handle file or database based on the config
    $storage->saveUserData([$email => $newUserData]);

    return array('success' => true);
  }



  public function authenticate($email, $password)
  {
    $storage = $this->storage;

    // Check if the email and password are empty
    if (empty($email) || empty($password)) {
      return array('error' => 'Please enter your email and password'); // Return error message if either field is empty
    }

    // Read the user data from storage (file and database)
    $existingUserData = $storage->getUserData();

    // Combine both file and database data into one array
    $allUsersData = array_merge($existingUserData['file'] ?? [], $existingUserData['database'] ?? []);

    // Check if the user exists in the combined data
    if (isset($allUsersData[$email])) {
      $user = $allUsersData[$email];

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
    return array('error' => 'Email not found');
  }
}