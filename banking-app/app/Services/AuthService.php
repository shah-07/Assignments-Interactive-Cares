<?php

namespace App\Services;

use App\Models\User;

class AuthService {
    public static function register($name, $email, $password) {
        // Load users from file
        $users = json_decode(file_get_contents('storage/users.json'), true) ?: [];
        
        // Check if email is already registered
        if (isset($users[$email])) {
            throw new \Exception('Email already registered.');
        }

        // Register new user
        $user = new User($name, $email, $password);
        $users[$email] = $user;
        file_put_contents('storage/users.json', json_encode($users));
        
        return $user;
    }

    public static function login($email, $password) {
        $users = json_decode(file_get_contents('storage/users.json'), true);

        if (!isset($users[$email]) || !password_verify($password, $users[$email]['password'])) {
            throw new \Exception('Invalid credentials.');
        }

        $_SESSION['user'] = $users[$email];
        return true;
    }
}
