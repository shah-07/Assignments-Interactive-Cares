<?php

namespace App\Controllers\Customer;
use App\Services\TransactionService;


class Dashboard
{

    public function view()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        } else {
            $getBalance = new TransactionService;
            $currentBal = $getBalance::getBalance();
            include '../views/customer/dashboard.php';
        }
    }
}