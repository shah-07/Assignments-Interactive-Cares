<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\TransactionService;

class CustomerController {
    public function register($name, $email, $password) {
        return AuthService::register($name, $email, $password);
    }

    public function login($email, $password) {
        return AuthService::login($email, $password);
    }

    public function deposit($amount) {
        return TransactionService::deposit($_SESSION['user'], $amount);
    }

    public function withdraw($amount) {
        return TransactionService::withdraw($_SESSION['user'], $amount);
    }

    public function transfer($toEmail, $amount) {
        return TransactionService::transfer($_SESSION['user'], $toEmail, $amount);
    }

    public function viewBalance() {
        return $_SESSION['user']->balance;
    }

    public function viewTransactions() {
        return $_SESSION['user']->transactions;
    }
}
