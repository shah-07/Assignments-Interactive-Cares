<?php

namespace App\Controllers;

use App\Services\TransactionService;

class AdminController {
    public function viewAllTransactions() {
        return TransactionService::getAllTransactions();
    }

    public function searchTransactionsByEmail($email) {
        return TransactionService::getTransactionsByEmail($email);
    }

    public function viewAllCustomers() {
        return TransactionService::getAllUsers();
    }
}
