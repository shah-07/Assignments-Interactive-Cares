<?php

namespace App\Controllers\Customer;
use App\Services\TransactionService;


class Withdraw
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
            $this->transactionService::withdraw($amount);

            header('Location: /withdraw');
        } else {
            $currentBal = $this->transactionService::getBalance();
            include '../views/customer/withdraw.php';
        }
    }
}