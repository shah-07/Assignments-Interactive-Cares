<?php

namespace App\Storage;

class Storage
{
  public $userDataFile;
  public $transactionsData;

  public function __construct()
  {
    $this->userDataFile = dirname(__DIR__) . '/Storage/users.json';
    $this->transactionsData = dirname(__DIR__) . '/Storage/transactions.json';
  }
}