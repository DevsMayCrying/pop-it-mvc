<?php

namespace App\Controllers;

use App\Models\User;
use App\Middleware\AuthMiddleware;
use Src\View;

class AdminController
{
    public function __construct()
    {
        // Только администраторы имеют доступ ко всем методам этого контроллера
        AuthMiddleware::admin();
    }
    
    /**
     * Список библиотекарей
     */
    public function index()
    {
        $users = User::where('role', 'librarian')->orderBy('created_at', 'desc')->get();
        
        $view = new View('admin/index', [
            'title' => 'Управление библиотекарями',
            'users' => $users
        ]);
        echo $view;
    }
    
    /**
     * Форма добавления библиотекаря
     */
    public function create()
    {
        $view = new View('admin/create', ['title' => 'Добавить библиотекаря']);
        echo $view;
    }
    
    /**
     * Сохранение нового библиотекаря
     */
    public function store()
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $full_name = trim($_POST['full_name'] ?? '');
        
        // Валидация
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'Логин обязателен';
        }
        
        if (empty($password)) {
            $errors[] = 'Пароль обязателен';
        } elseif (strlen($password) < 4) {
            $errors[] = 'Пароль должен быть не менее 4 символов';
        }
        
        if (empty($full_name)) {
            $errors[] = 'ФИО обязательно';
        }
        
        // Проверка на существование пользователя
        if (User::where('username', $username)->exists()) {
            $errors[] = 'Пользователь с таким логином уже существует';
        }
        
        if (!empty($errors)) {
            session_start();
            $_SESSION['errors'] = $errors;
            header('Location: /admin/users/create');
            exit;
        }
        
        // Создаем библиотекаря
        User::create([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'librarian',
            'full_name' => $full_name
        ]);
        
        session_start();
        $_SESSION['success'] = 'Библиотекарь успешно добавлен';
        header('Location: /admin/users');
        exit;
    }
    
    /**
     * Удаление библиотекаря
     */
    public function delete($id)
    {
        $user = User::find($id);
        
        // Нельзя удалить самого себя
        if ($user && $user->id != $_SESSION['user_id']) {
            $user->delete();
            $_SESSION['success'] = 'Библиотекарь удален';
        } else {
            $_SESSION['error'] = 'Нельзя удалить этого пользователя';
        }
        
        header('Location: /admin/users');
        exit;
    }
}
