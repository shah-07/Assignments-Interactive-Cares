<?php

namespace App\Models;

class Transaction {
    public $type;
    public $amount;
    public $date;
    public $toEmail = null;

    public function __construct($type, $amount, $date, $toEmail = null) {
        $this->type = $type;
        $this->amount = $amount;
        $this->date = $date;
        $this->toEmail = $toEmail;
    }
}
