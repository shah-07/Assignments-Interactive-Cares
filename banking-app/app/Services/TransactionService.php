<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService {
    public static function deposit($user, $amount) {
        $user->balance += $amount;
        $transaction = new Transaction('Deposit', $amount, date('Y-m-d H:i:s'));
        $user->transactions[] = $transaction;
        self::saveUser($user);
    }

    public static function withdraw($user, $amount) {
        if ($user->balance < $amount) {
            throw new \Exception('Insufficient balance.');
        }

        $user->balance -= $amount;
        $transaction = new Transaction('Withdraw', $amount, date('Y-m-d H:i:s'));
        $user->transactions[] = $transaction;
        self::saveUser($user);
    }

    public static function transfer($fromUser, $toEmail, $amount) {
        $users = json_decode(file_get_contents('storage/users.json'), true);
        
        if (!isset($users[$toEmail])) {
            throw new \Exception('Recipient not found.');
        }

        if ($fromUser->balance < $amount) {
            throw new \Exception('Insufficient balance.');
        }

        $fromUser->balance -= $amount;
        $recipient = $users[$toEmail];
        $recipient->balance += $amount;

        $transaction = new Transaction('Transfer', $amount, date('Y-m-d H:i:s'), $toEmail);
        $fromUser->transactions[] = $transaction;
        $recipient->transactions[] = new Transaction('Received', $amount, date('Y-m-d H:i:s'), $fromUser->email);

        self::saveUser($fromUser);
        self::saveUser($recipient);
    }

    public static function saveUser($user) {
        $users = json_decode(file_get_contents('storage/users.json'), true);
        $users[$user->email] = $user;
        file_put_contents('storage/users.json', json_encode($users));
    }

    public static function getAllTransactions() {
        return json_decode(file_get_contents('storage/transactions.json'), true);
    }

    public static function getTransactionsByEmail($email) {
        $users = json_decode(file_get_contents('storage/users.json'), true);
        return $users[$email]['transactions'] ?? [];
    }

    public static function getAllUsers() {
        return json_decode(file_get_contents('storage/users.json'), true);
    }
}
