<?php

namespace App\Controllers\Customer;
use App\Services\TransactionService;


class Transfer
{
    private $transactionService;

    public function __construct()
    {
        // Initialize the TransactionService instance
        $this->transactionService = new TransactionService();
    }

    public function view()
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