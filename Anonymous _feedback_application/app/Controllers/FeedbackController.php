<?php

namespace App\Controllers;

use App\Models\Feedback;

class FeedbackController
{
  public function run()
  {
    echo "Bus is running" .
      PHP_EOL;
  }
  public function submitFeedback()
  {
    $feedback = new Feedback();
    $feedback->save($_POST['feedback']);
  }
}