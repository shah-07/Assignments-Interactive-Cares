<?php

namespace App\Services;

use App\Models\Transaction;
use App\Storage\Storage;

class TransactionService
{
    private static $userData;
    private static $transactions;
    private static $email;

    public function __construct()
    {
        self::$userData = (new Storage())->userDataFile;
        self::$transactions = (new Storage())->transactionsData;

        // Check if the cookie exists before assigning it
        self::$email = $_COOKIE['email'] ?? null;
    }
    public static function deposit($amount)
    {
        $currentBalance = self::getBalance();

        $newBalance = $currentBalance + abs($amount);

        $transaction = new Transaction('Deposit', $amount, date('d M Y, h:i A'));
        self::addTransaction($transaction);

        self::updateUserBalance($newBalance);
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

        // Create a withdrawal transaction entry
        $transaction = new Transaction('Withdraw', $amount, date('d M Y, h:i A'));
        self::addTransaction($transaction);

        // Update the user's balance
        self::updateUserBalance($newBalance);

        return ['success' => 'Withdrawal successful', 'new_balance' => $newBalance];
    }

    public static function transfer($toEmail, $amount)
    {
        // Get the current user's balance
        $currentBalance = self::getBalance();

        // Ensure the amount is positive and the current balance is sufficient
        if ($amount > 0 && $currentBalance >= $amount) {
            // Get users from the JSON file
            $users = json_decode(file_get_contents(self::$userData), true);

            // Check if recipient exists
            if (isset($users[$toEmail])) {
                // Update the recipient's balance
                $users[$toEmail]['balance'] += $amount; // Increase recipient's balance

                // Deduct the amount from the current user's balance
                $newBalance = $currentBalance - $amount;

                // Save the updated user data back to the JSON file
                self::updateUserBalance($newBalance);

                // Create a new transaction record (optional)
                $transaction = new Transaction('Transfer', $amount, date('d M Y, h:i A'), self::$email, $toEmail);

                self::addTransaction($transaction);

                return ['success' => true, 'message' => 'Transfer successful.'];
            } else {
                return ['error' => 'Recipient not found.'];
            }
        } else {
            return ['error' => 'Insufficient balance or invalid amount.'];
        }
    }


    public static function addTransaction($transaction)
    {
        $users = json_decode(file_get_contents(self::$transactions), true);
        $users[self::$email][] = $transaction;
        file_put_contents(self::$transactions, json_encode($users));
    }

    public static function getAllTransactions()
    {
        return json_decode(file_get_contents('storage/transactions.json'), true);
    }

    public static function getTransactionsByEmail($email)
    {
        $users = json_decode(file_get_contents('storage/users.json'), true);
        return $users[$email]['transactions'] ?? [];
    }

    public static function getAllUsers()
    {
        return json_decode(file_get_contents('storage/users.json'), true);
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

}
