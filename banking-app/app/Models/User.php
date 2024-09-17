<?php

namespace App\Models;

class User {
    public $name;
    public $email;
    public $password;
    public $balance = 0;
    public $transactions = [];

    public function __construct($name, $email, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }
}
