<?php

namespace App\Controllers\Customer;
use App\Services\TransactionService;


class Deposit
{
    private $transactionService;

    public function __construct()
    {
        // Initialize the TransactionService instance
        $this->transactionService = new TransactionService();
    }

    public function view()
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
}