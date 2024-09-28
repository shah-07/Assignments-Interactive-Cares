<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\TransactionService;

class CustomerController
{
    private $authService;
    private $transactionService;

    public function __construct()
    {
        // Initialize the TransactionService instance
        $this->transactionService = new TransactionService();

        // Initialize the AuthService instance
        $this->authService = new AuthService();


    }
    public function register()
    {
        $this->authService::register();
    }

    public function login()
    {
        $this->authService::login();
    }

    public function logout()
    {
        $this->authService::logout();
    }

    public function dashboard()
    {
        $currentBal = $this->transactionService::getBalance();
        $transactions = $this->transactionService::getTransactionsByEmail();
        include '../views/customer/dashboard.php';
    }

    public function deposit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = $_POST['amount'];
            $this->transactionService::deposit($amount);

            header('Location: /deposit');
        } else {
            $currentBal = $this->transactionService::getBalance();
            include '../views/customer/deposit.php';
        }
    }

    public function withdraw()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = $_POST['amount'];
            $this->transactionService::withdraw($amount);

            header('Location: /withdraw');
        } else {
            $currentBal = $this->transactionService::getBalance();
            include '../views/customer/withdraw.php';
        }
    }

    public function transfer()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $amount = $_POST['amount'];
            $recipientEmail = $_POST['email'];
            $this->transactionService::transfer($recipientEmail, $amount);

            header('Location: /transfer');
        } else {
            $currentBal = $this->transactionService::getBalance();
            include '../views/customer/transfer.php';
        }
    }
}
