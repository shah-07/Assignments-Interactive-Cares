<?php

namespace App\Services;

use App\Models\Transaction;
use App\Storage\Storage;

class TransactionService
{
    private static $userData;
    private static $transactionsData;
    private static $storage;
    private static $email;

    public function __construct()
    {
        self::$userData = (new Storage())->userDataFile;
        self::$transactionsData = (new Storage())->transactionsData;

        self::$storage = new Storage();

        // Check if the cookie exists before assigning it
        self::$email = $_COOKIE['email'] ?? null;
    }

    // Common function to handle transaction
    private static function handleTransaction($type, $amount, $newBalance)
    {
        // Create a transaction entry
        $transaction = new Transaction($type, $amount, date('d M Y, h:i:s A'));

        // Add the transaction
        self::addTransaction($transaction);

        // Update the user's balance
        self::updateUserBalance($newBalance);
    }
    public static function deposit($amount)
    {
        $currentBalance = self::getBalance();

        $newBalance = $currentBalance + abs($amount);

        // Call the common method with 'Deposit' type
        self::handleTransaction('Deposit', $amount, $newBalance);
    }

    public static function withdraw($amount)
    {
        $currentBalance = self::getBalance();

        // Check if the user has sufficient balance
        if ($amount > $currentBalance) {
            return ['error' => 'Insufficient balance for the withdrawal'];
        }

        // Calculate the new balance
        $newBalance = $currentBalance - abs($amount);

        // Call the common method with 'Withdraw' type
        self::handleTransaction('Withdraw', $amount, $newBalance);

        return ['success' => 'Withdrawal successful', 'new_balance' => $newBalance];
    }

    public static function addTransaction($transaction, $email = null)
    {
        // Use self::$email if $email is not provided
        if ($email === null) {
            $email = self::$email;
        }

        self::$storage->saveTransactionData($transaction, $email);
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

    public static function getBalance()
    {
        $users = self::getAllUsers();

        // Ensure that the users data is an array
        if (is_array($users)) {
            // Check if the email exists in the flattened array
            if (isset($users[self::$email])) {
                return $users[self::$email]['balance']; // Return the balance of the found user
            }
        }

        return null; // Return null if no user is found
    }

    public static function getSenderName($email)
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

    public static function updateUserBalance($balance)
    {
        // Get users from the storage
        $users = self::$storage->getUserData();

        // Ensure balance is set
        if (isset($balance)) {
            // Check if the 'file' key exists and if the email exists in file storage
            if (isset($users['file']) && isset($users['file'][self::$email])) {
                // Update the balance for the user in file storage
                $users['file'][self::$email]['balance'] = $balance;

                // Save the updated users array back to the JSON file
                file_put_contents(self::$userData, json_encode($users['file']));
                return true; // Indicate success
            }

            // Check if the 'database' key exists and if the email exists in database storage
            if (isset($users['database']) && isset($users['database'][self::$email])) {
                $email = self::$email;
                self::$storage->updateBalance($email, $balance);
            }

            // If the email does not exist in either storage
            echo 'Email not found in user data';
        } else {
            echo 'Balance not set';
        }

        return false; // Indicate failure if email not found or balance not set
    }


    public static function transfer($toEmail, $amount)
    {
        // Get the current user's balance
        $currentBalance = self::getBalance();

        if ($toEmail === self::$email) {
            return ['error' => 'You cannot transfer to your own account'];
        }

        // Ensure the amount is positive and the current balance is sufficient
        if ($amount > 0 && $currentBalance >= $amount) {
            // Fetch user data from the storage
            $users = self::$storage->getUserData();

            // Check for file-based storage
            if (isset($users['file'])) {
                // Ensure recipient exists in file storage
                if (isset($users['file'][$toEmail])) {
                    // Update balances
                    $users['file'][$toEmail]['balance'] += $amount;  // Increase recipient's balance
                    $users['file'][self::$email]['balance'] = $currentBalance - $amount;  // Decrease sender's balance

                    // Save the updated user data back to the JSON file
                    file_put_contents(self::$userData, json_encode($users['file']));

                    // Log transactions
                    $transaction = new Transaction('Received', $amount, date('d M Y, h:i:s A'), self::$email, $toEmail);
                    self::addTransaction($transaction, $toEmail);

                    $transaction = new Transaction('Transfer', $amount, date('d M Y, h:i:s A'), self::$email, $toEmail);
                    self::addTransaction($transaction);

                    return ['success' => true, 'message' => 'Transfer successful (file storage).'];
                } else {
                    return ['error' => 'Recipient not found in file storage.'];
                }
            }

            // Check for database-based storage
            if (isset($users['database'])) {
                // Check if the recipient exists in the database
                if (isset($users['database'][$toEmail])) {
                    // Update recipient's balance in the database
                    $newRecipientBalance = $users['database'][$toEmail]['balance'] + $amount;
                    self::$storage->updateBalance($toEmail, $newRecipientBalance);

                    // Update the sender's balance in the database
                    $newSenderBalance = $currentBalance - $amount;
                    self::$storage->updateBalance(self::$email, $newSenderBalance);

                    // Log transactions in the database
                    $transaction = new Transaction('Received', $amount, date('d M Y, h:i:s A'), self::$email, $toEmail);
                    self::addTransaction($transaction, $toEmail);

                    $transaction = new Transaction('Transfer', $amount, date('d M Y, h:i:s A'), self::$email, $toEmail);
                    self::addTransaction($transaction);

                    return ['success' => true, 'message' => 'Transfer successful (database storage).'];
                } else {
                    return ['error' => 'Recipient not found in database storage.'];
                }
            }
        } else {
            return ['error' => 'Insufficient balance or invalid amount.'];
        }
    }


    public static function getTransactionsByEmail()
    {
        // Get transactions from the JSON file
        $transactions = self::$storage->getTransactionData();

        $userTransactions = [];

        // Loop through all users' transactions
        foreach ($transactions as $email => $transactionList) {
            foreach ($transactionList as $transaction) {
                // Check if the user is involved in the transaction (either as sender or receiver)
                if (
                    (!empty($transaction['toEmail']) && $transaction['toEmail'] === self::$email) || // Received
                    (!empty($transaction['fromEmail']) && $transaction['fromEmail'] === self::$email) // Sent
                ) {
                    // Initialize the usernames array if it doesn't exist
                    if (!isset($transaction['sendername'])) {
                        $transaction['sendername'] = []; // Create a new array for usernames
                    }

                    if ($transaction['type'] != 'Received') {
                        if ($transaction['toEmail'] === self::$email) {
                            $userName = self::getSenderName($transaction['fromEmail']);

                            $transaction['sendername'] = $userName;
                        } else {
                            $userName = self::getSenderName($transaction['toEmail']);

                            $transaction['sendername'] = $userName;
                        }

                        $userTransactions[] = $transaction;
                    }
                }
            }
        }

        // Sorting the transactions array by date
        usort($userTransactions, function ($a, $b) {
            $dateA = strtotime($a['date']);
            $dateB = strtotime($b['date']);
            $dateComparison = $dateB <=> $dateA;

            // Return the date comparison result
            return $dateComparison;
        });


        return $userTransactions;
    }

}