<?php

namespace App\Controllers;
use App\Storage\Storage;


class DashboardController
{
  public function dashboard()
  {
    if (isset($_COOKIE["uniqueKey"])) {
      // Retrieve the feedback for the current user
      $feedbackDataFile = (new Storage())->feedbackDataFile;
      $fileContents = file_get_contents($feedbackDataFile);
      $lines = explode("\n", $fileContents);
      $feedbacks = array();
      foreach ($lines as $line) {
        $feedbackData = json_decode($line, true);
        if ($feedbackData) {
          $feedbacks[] = $feedbackData;
        }
      }

      // Filter the feedbacks to get the ones for the current user
      $currentUsername = $_COOKIE['username'];
      $currentFeedbacks = array_filter($feedbacks, function ($feedback) use ($currentUsername) {
        return $feedback['username'] == $currentUsername;
      });


      require_once __DIR__ . '/../Views/dashboard.php';
    } else {
      header('Location: /login');
    }
  }
}