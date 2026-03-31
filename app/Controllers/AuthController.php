<?php
// app/Controllers/AuthController.php

namespace App\Controllers;

use App\Auth;

class AuthController
{
    public function showLogin()
    {
        if (Auth::check()) {
            header('Location: /dashboard');
            exit;
        }

        // Исправленный путь к вашему файлу
        include __DIR__ . '/../../views/auth/login.php';
    }

    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($username, $password)) {
            header('Location: /dashboard');
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['error'] = 'Неверный логин или пароль';
        header('Location: /login');
        exit;
    }

    public function logout()
    {
        Auth::logout();
        header('Location: /login');
        exit;
    }
}