<?php

namespace App\Storage;

class Storage
{
  public $userDataFile;
  public $feedbackDataFile;

  public function __construct()
  {
    $this->userDataFile = dirname(__DIR__) . '/Storage/users.txt';
    $this->feedbackDataFile = dirname(__DIR__) . '/Storage/feedbacks.txt';
  }
}