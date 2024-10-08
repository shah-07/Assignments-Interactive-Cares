<?php

namespace App\Controllers;

use App\Services\TransactionService;
use App\Storage\Storage;

class AdminController
{
    private static $storage;

    public function __construct()
    {
        self::$storage = new Storage();
    }

    public static function getAllUsers()
    {
        // Get users from the storage
        $users = self::$storage->getUserData();

        // Flatten the user data from both sources into a single array
        $allUsers = [];

        // Merge users from 'file' source if exists
        if (isset($users['file'])) {
            $allUsers = array_merge($allUsers, $users['file']);
        }

        // Merge users from 'database' source if exists
        if (isset($users['database'])) {
            $allUsers = array_merge($allUsers, $users['database']);
        }

        return $allUsers;
    }

    public function viewAllCustomers()
    {
        $userData = self::getAllUsers();
        $customers = [];

        foreach ($userData as $email => $data) {
            $customers[] = [
                'name' => $data['username'],
                'email' => $email
            ];
        }
        include '../views/admin/customers.php';
    }

    public function viewAllTransactions()
    {
        $transactionsData = self::$storage->getTransactionData();
        $transactions = [];

        // Load user data to get usernames based on emails
        $userData = self::getAllUsers();

        foreach ($transactionsData as $email => $transactionArray) {
            foreach ($transactionArray as $transaction) {
                // Get customer name
                $customerName = isset($userData[$email]) ? $userData[$email]['username'] : 'Unknown';

                // Handle Deposit and Withdraw transactions
                if ($transaction['type'] === 'Deposit' || $transaction['type'] === 'Withdraw') {
                    $transactions[] = [
                        'name' => $customerName,
                        'amount' => ($transaction['type'] === 'Deposit' ? '+' : '-') . $transaction['amount'],
                        'date' => $transaction['date'],
                    ];
                }
                // Handle Transfer transactions (exclude 'Received')
                elseif ($transaction['type'] !== 'Received' && $transaction['toEmail'] !== null && $transaction['fromEmail'] !== null) {
                    $transactions[] = [
                        'name' => $customerName,
                        'amount' => '-' . $transaction['amount'], // Deducting transferred amount
                        'date' => $transaction['date'],
                    ];
                }
            }
        }


        // Sort transactions by date and then by amount
        usort($transactions, function ($a, $b) {
            // Convert the date string to a timestamp for comparison
            $dateA = strtotime($a['date']);
            $dateB = strtotime($b['date']);
            $dateComparison = $dateB <=> $dateA;

            // Return the date comparison result
            return $dateComparison;
        });


        // Include the transactions view file
        include '../views/admin/transactions.php';
    }

    public function viewCustomerTransactions()
    {
        // Get the email from the query string
        $useremail = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : null;

        if ($useremail) {
            $userData = self::getAllUsers();
            $username = $userData[$useremail]["username"];

            $transactions = $this->getCustomerNameByEmail($useremail);

            // Include the transactions view file
            include '../views/admin/customer_transactions.php';
        }

    }
    public static function getReceiverName($email)
    {
        // Get users from the JSON file
        $users = self::getAllUsers();

        // Ensure that the users data is an array and not empty
        if (is_array($users) && !empty($users)) {
            // Directly check if the email exists in the associative array
            if (isset($users[$email])) {
                return $users[$email]['username']; // Return the username of the found user
            }
        }

        return null; // Return null if no user is found
    }

    private function getCustomerNameByEmail($useremail)
    {
        // Get transactions from the JSON file and decode it into an associative array
        $transactions = self::$storage->getTransactionData();

        // Initialize an array to store the user's transactions
        $userTransactions = [];

        // Loop through all transactions associated with each user (identified by email)
        foreach ($transactions as $email => $transactionList) {
            if ($email !== $useremail) {
                continue;
            }

            foreach ($transactionList as $transaction) {
                // Set default values for 'receivername' and 'email'
                $transaction['receivername'] = $transaction['receivername'] ?? [];
                $transaction['email'] = $transaction['email'] ?? [];

                // Determine the relevant email and user name based on 'toEmail' and 'fromEmail'
                if (!empty($transaction['toEmail']) && !empty($transaction['fromEmail'])) {
                    $relatedEmail = ($transaction['toEmail'] === $email) ? $transaction['fromEmail'] : $transaction['toEmail'];
                    $transaction['receivername'] = self::getReceiverName($relatedEmail);
                    $transaction['email'] = $relatedEmail;
                } else {
                    $transaction['receivername'] = self::getReceiverName($email);
                    $transaction['email'] = $email;
                }

                // Remove unnecessary email fields
                unset($transaction['toEmail'], $transaction['fromEmail']);

                // Format the amount based on the transaction type
                $transaction['amount'] = (($transaction['type'] === 'Deposit' || $transaction['type'] === 'Received') ? '+' : '-') . '$' . $transaction['amount'];

                // Add the processed transaction to the user's transactions array
                $userTransactions[] = $transaction;
            }
        }

        // Sort the transactions by date in descending order
        usort($userTransactions, function ($a, $b) {
            return strtotime($b['date']) <=> strtotime($a['date']);
        });

        // Return the sorted list of user transactions
        return $userTransactions;
    }


}
