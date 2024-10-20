<?php

namespace App\Models;
use App\Storage\Storage;

class Feedback
{
  public function save($username, $feedbackText)
  {
    // Store the feedback associated with the username
    $feedbackDataFile = (new Storage())->feedbackDataFile;
    if ($username && $feedbackText) {
      $data = array('username' => $username, 'feedback' => $feedbackText);
      $json = json_encode($data);
      $file = fopen($feedbackDataFile, 'a');
      fwrite($file, $json . "\n");
      fclose($file);

      // Redirect the user to the feedback-success.php page
      header('Location: /feedback-success');
      exit;
    }
  }
}