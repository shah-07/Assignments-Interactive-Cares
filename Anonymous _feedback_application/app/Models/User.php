<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
class User
{
  private $userDataFile = '../users.txt';

  public function register($username, $email, $password, $confirm_password)
  {
    // Validate the form data
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
      return false;
    } elseif ($password != $confirm_password) {
      return false;
    } else {
      // Hash the password
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Generate a unique key
      $uniqueKey = md5(uniqid(rand(), true));

      // Create a user data array
      $userData = array(
        'uniqueKey' => $uniqueKey,
        'username' => $username,
        'email' => $email,
        'password' => $hashed_password
      );

      // Read existing user data from the file
      $existingUserData = array();
      if (file_exists($this->userDataFile)) {
        $existingUserData = json_decode(file_get_contents($this->userDataFile), true);
      }

      // Add the new user data to the existing data
      $existingUserData[] = $userData;

      // Write the updated user data to the file
      file_put_contents($this->userDataFile, json_encode($existingUserData));

      return $uniqueKey;
    }
  }
  public function authenticate($username, $password)
  {
    $file = fopen(USER_FILE, 'r');
    while (($line = fgets($file)) !== false) {
      list($storedUsername, $storedPassword) = explode(',', $line);
      if ($storedUsername === $username && $storedPassword === $password) {
        return true;
      }
    }
    fclose($file);
    return false;
  }
}