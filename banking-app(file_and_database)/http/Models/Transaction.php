<?php

namespace App\Models;

class Transaction
{
    public $type;
    public $amount;
    public $date;
    public $toEmail = null;
    public $fromEmail = null;

    public function __construct($type, $amount, $date, $fromEmail = null, $toEmail = null)
    {
        $this->type = $type;
        $this->amount = $amount;
        $this->date = $date;
        $this->fromEmail = $fromEmail;
        $this->toEmail = $toEmail;
    }
}
