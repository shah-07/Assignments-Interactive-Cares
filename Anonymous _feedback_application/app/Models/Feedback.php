<?php

namespace App\Models;

class Feedback
{
  public function save($feedback)
  {
    $file = fopen(FEEDBACK_FILE, 'a');
    fwrite($file, $feedback . "\n");
    fclose($file);
  }
}