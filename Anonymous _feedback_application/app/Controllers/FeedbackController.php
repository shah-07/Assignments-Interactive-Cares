<?php

namespace App\Controllers;

use App\Models\Feedback;
use App\Storage\Storage;

class FeedbackController
{
  public function submitFeedback($uniqueKey, $username)
  {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $feedbackText = $_POST['feedback'];

      $userDataFile = (new Storage())->userDataFile;

      // Get the user data from the file
      $user_data = json_decode(file_get_contents($userDataFile), true);

      // Find the user associated with the unique key
      $username = '';
      foreach ($user_data as $user) {
        if ($user['uniqueKey'] === $uniqueKey) {
          $username = $user['username'];
          break;
        }
      }

      $feedback = new Feedback();
      $feedback->save($username, $feedbackText);
    } else {
      require_once __DIR__ . '/../Views/feedback.php';
    }
  }
}