<?php

namespace App\Services;

use App\Models\Transaction;
use App\Storage\Storage;

class TransactionService
{
    private static $userData;
    private static $transactionsData;
    private static $email;

    public function __construct()
    {
        self::$userData = (new Storage())->userDataFile;
        self::$transactionsData = (new Storage())->transactionsData;

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

        $users = json_decode(file_get_contents(self::$transactionsData), true);
        $users[$email][] = $transaction;
        file_put_contents(self::$transactionsData, json_encode($users));
    }
    public static function getBalance()
    {
        // Get users from the JSON file
        $users = json_decode(file_get_contents(self::$userData), true); // Access static property

        // Ensure that the users data is an array and not empty
        if (is_array($users) && !empty($users)) {
            // Directly check if the email exists in the associative array
            if (isset($users[self::$email])) {
                return $users[self::$email]['balance']; // Return the balance of the found user
            }
        }

        return null; // Return null if no user is found
    }
    public static function getSenderName($email)
    {
        // Get users from the JSON file
        $users = json_decode(file_get_contents(self::$userData), true); // Access static property

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
        // Ensure balance is set
        if (isset($balance)) {
            // Get users from the JSON file
            $users = json_decode(file_get_contents(self::$userData), true); // Access static property

            // Check if the email exists in the users array
            if (isset($users[self::$email])) {
                // Update the balance for the user
                $users[self::$email]['balance'] = $balance;

                // Save the updated users array back to the JSON file
                file_put_contents(self::$userData, json_encode($users));
            } else {
                echo 'Email not found in user data';
            }
        } else {
            echo 'Balance not set';
        }
    }

    public static function transfer($toEmail, $amount)
    {
        // Get the current user's balance
        $currentBalance = self::getBalance();

        if ($toEmail === self::$email) {
            return ['error' => 'You can not transfer your own acount'];
        }

        // Ensure the amount is positive and the current balance is sufficient
        if ($amount > 0 && $currentBalance >= $amount) {
            // Get users from the JSON file
            $users = json_decode(file_get_contents(self::$userData), true);

            // Check if recipient exists
            if (isset($users[$toEmail])) {
                // Update the recipient's balance
                $users[$toEmail]['balance'] += $amount; // Increase recipient's balance

                // Deduct the amount from the current user's balance
                $users[self::$email]['balance'] = $currentBalance - $amount;

                $transaction = new Transaction('Received', $amount, date('d M Y, h:i:s A'), self::$email, $toEmail);
                self::addTransaction($transaction, $toEmail);

                // Save the updated user data back to the JSON file
                file_put_contents(self::$userData, json_encode($users));

                // Create a new transaction record (optional)
                $transaction = new Transaction('Transfer', $amount, date('d M Y, h:i:s A'), self::$email, $toEmail);

                self::addTransaction($transaction);

                return ['success' => true, 'message' => 'Transfer successful.'];
            } else {
                return ['error' => 'Recipient not found.'];
            }
        } else {
            return ['error' => 'Insufficient balance or invalid amount.'];
        }
    }

    public static function getTransactionsByEmail()
    {
        // Get transactions from the JSON file
        $transactions = json_decode(file_get_contents(self::$transactionsData), true);

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
