<?php
namespace App\Routes;

use App\Controllers\DashboardController;
use App\Controllers\FeedbackController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\RegisterController;
use App\Storage\Storage;

//welcome page
if ($_SERVER['REQUEST_URI'] === '/') {
  require_once __DIR__ . '/../Views/welcome.php';
}

//Register route
if ($_SERVER['REQUEST_URI'] === '/register') {
  $registerController = new RegisterController();
  $registerController->register();
}

//Login route
if ($_SERVER['REQUEST_URI'] === '/login') {
  $loginController = new LoginController();
  $loginController->login();
}

//dashboard
if ($_SERVER['REQUEST_URI'] === '/dashboard') {
  $dashboardController = new DashboardController();
  $dashboardController->dashboard();
}

//feedback
if (preg_match('/^\/feedback\/(.+)$/', $_SERVER['REQUEST_URI'], $matches)) {
  $uniqueKey = $matches[1];

  $userDataFile = (new Storage())->userDataFile;

  // Read the user data from the file
  $fileContents = file_get_contents($userDataFile);
  $users = json_decode($fileContents, true);

  // Initialize a flag to check if the unique key exists
  $key_exists = false;

  // Iterate through the user data
  foreach ($users as $user) {
    if ($user['uniqueKey'] === $uniqueKey) {
      $username = $user['username'];
      $key_exists = true;
      break;
    }
  }

  if ($key_exists) {
    $feedbackController = new FeedbackController();
    $feedbackController->submitFeedback($uniqueKey, $username);

  } else {
    // Handle the case where the unique key does not exist
    echo "Invalid unique key";
  }
}

//feedback success
if ($_SERVER['REQUEST_URI'] === '/feedback-success') {
  require_once __DIR__ . '/../Views/feedback-success.php';
}

//logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
  $logoutController = new LogoutController();
  $logoutController->logout();
  // Redirect the user to the login page or another page
  header('Location: /login');
  exit;
}