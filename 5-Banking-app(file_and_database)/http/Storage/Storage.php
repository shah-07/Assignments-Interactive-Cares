<?php

namespace App\Storage;

use PDO;

class Storage
{
  private $config;
  private $db;
  public $userDataFile;
  public $transactionsData;

  public function __construct()
  {
    // Load configuration
    $this->config = include dirname(__DIR__) . '/config/config.php';

    try {
      if ($this->config['storage'] === 'file') {
        $this->userDataFile = dirname(__DIR__) . '/Storage/files/users.json';
        $this->transactionsData = dirname(__DIR__) . '/Storage/files/transactions.json';
      } elseif ($this->config['storage'] === 'database') {
        $this->connectDatabase();
      }
    } catch (\PDOException $e) {
      // Log the error and display a custom error message
      error_log("Database connection failed: " . $e->getMessage());
      die("Error: Unable to connect to the database. Please try again later.");
    }
  }

  private function connectDatabase()
  {
    $dbConfig = $this->config['db'];
    $dsn = 'mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'];
    $this->db = new PDO($dsn, $dbConfig['user'], $dbConfig['password']);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }


  public function getUserData()
  {
    $users = []; // Initialize an empty array

    // Handling file storage
    if ($this->config['storage'] === 'file') {
      $fileData = json_decode(file_get_contents($this->userDataFile), true);
      $users['file'] = $fileData; // Store file data under the 'file' key
    }

    // Handling database storage
    if ($this->config['storage'] === 'database') {
      $stmt = $this->db->query('SELECT * FROM users');
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Transform the result into the desired format
      $formattedData = [];
      foreach ($data as $user) {
        $email = $user['email']; // Use email as the key
        $formattedData[$email] = [
          'username' => $user['username'],
          'email' => $user['email'],
          'password' => $user['password'],
          'balance' => (float) $user['balance'] // Ensure balance is cast to float
        ];
      }

      $users['database'] = $formattedData; // Store database data under the 'database' key
    }

    return $users;
  }



  public function saveUserData($data)
  {
    if ($this->config['storage'] === 'file') {
      file_put_contents($this->userDataFile, json_encode($data));
    } elseif ($this->config['storage'] === 'database') {
      foreach ($data as $email => $user) {
        // First, check if a user with the same email or username exists
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email OR username = :username');
        $stmt->execute([
          ':email' => $email,
          ':username' => $user['username']
        ]);

        $existingUser = $stmt->fetch();

        if (!$existingUser) {
          // Insert new user only if no existing email or username match is found
          $stmt = $this->db->prepare('INSERT INTO users (email, username, password, balance)
                                              VALUES (:email, :username, :password, :balance)');
          $stmt->execute([
            ':email' => $email,
            ':username' => $user['username'],
            ':password' => $user['password'],
            ':balance' => $user['balance']
          ]);
        } else {
          // Optionally, log or handle a message that the user exists
          echo 'User with this email or username already exists.';
        }
      }
    }
  }

  public function updateBalance($email, $newBalance)
  {
    // Update balance in the database
    $stmt = $this->db->prepare('UPDATE users SET balance = :balance WHERE email = :email');
    $stmt->execute([
      ':balance' => $newBalance,
      ':email' => $email
    ]);
    return true; // Indicate success
  }


  public function getTransactionData()
  {
    // Initialize an empty array to hold the transaction data
    $transactions = [];

    if ($this->config['storage'] === 'file') {
      // Get transaction data from file storage
      $transactions = json_decode(file_get_contents($this->transactionsData), true);
    } elseif ($this->config['storage'] === 'database') {
      // Fetch transactions from the database
      $stmt = $this->db->query('SELECT * FROM transactions');
      $dbTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Format the database transactions to match file data format
      foreach ($dbTransactions as $transaction) {
        $userEmail = $transaction['user'];
        $formattedTransaction = [
          'type' => $transaction['type'],
          'amount' => $transaction['amount'],
          'date' => $transaction['date'],
          'toEmail' => $transaction['toEmail'],
          'fromEmail' => $transaction['fromEmail']
        ];

        // Append the transaction to the user's transaction list
        if (!isset($transactions[$userEmail])) {
          $transactions[$userEmail] = []; // Initialize if not set
        }
        $transactions[$userEmail][] = $formattedTransaction;
      }
    }

    return $transactions;
  }

  public function saveTransactionData($data, $email)
  {
    if ($this->config['storage'] === 'file') {
      $users = json_decode(file_get_contents($this->transactionsData), true);

      // Append the new transaction to the user's list of transactions
      $users[$email][] = $data;

      file_put_contents($this->transactionsData, json_encode($users));
    } elseif ($this->config['storage'] === 'database') {
      // Handle a single transaction for a specific email
      $stmt = $this->db->prepare('INSERT INTO transactions (user, type, amount, date, toEmail, fromEmail) VALUES (:user, :type, :amount, :date, :toEmail, :fromEmail)');
      $stmt->execute([
        ':user' => $email,
        ':type' => $data->type,
        ':amount' => $data->amount,
        ':date' => $data->date,
        ':toEmail' => $data->toEmail,
        ':fromEmail' => $data->fromEmail
      ]);
    }
  }
}
