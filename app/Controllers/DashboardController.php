<?php

namespace App\Controllers;

use App\Auth;
use App\Models\Book;
use App\Models\Reader;
use App\Models\BookLoan;
use App\Middleware\AuthMiddleware;
use Src\View;

class DashboardController
{
    public function index()
    {
        // Проверка авторизации через middleware
        AuthMiddleware::check();

        // Получаем данные пользователя из сессии
        $user_name = $_SESSION['user_name'] ?? $_SESSION['username'] ?? 'Гость';
        $user_role = $_SESSION['user_role'] ?? 'user';

        // Исправляем подсчет активных выдач
        $active_loans = BookLoan::activeLoans()->count();

        $data = [
            'title' => 'Панель управления',
            'user_name' => $user_name,
            'user_role' => $user_role,
            'total_books' => Book::count(),
            'total_readers' => Reader::count(),
            'active_loans' => $active_loans
        ];

        $view = new View('dashboard/index', $data);
        echo $view;
    }
}