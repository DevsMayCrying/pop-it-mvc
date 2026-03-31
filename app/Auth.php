<?php
// app/Auth.php

namespace App;

use App\Models\User;

class Auth
{
    public static function attempt($username, $password)
    {
        $user = User::where('username', $username)->first();

        if ($user && password_verify($password, $user->password)) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name ?? $user->username; // Добавляем имя
            $_SESSION['username'] = $user->username;
            $_SESSION['user_role'] = $user->role ?? 'user';
            return true;
        }

        return false;
    }

    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
    }

    public static function user()
    {
        if (!self::check()) {
            return null;
        }

        return User::find($_SESSION['user_id']);
    }

    public static function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public static function isLibrarian()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'librarian';
    }
}