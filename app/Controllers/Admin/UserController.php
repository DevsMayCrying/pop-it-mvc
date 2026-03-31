<?php
// app/Controllers/Admin/UserController.php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Middleware\AuthMiddleware;
use Src\View;

class UserController
{
    public function index()
    {
        AuthMiddleware::check();

        // Проверка прав администратора
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $users = User::all();

        $view = new View('admin/users/index', [
            'title' => 'Управление пользователями',
            'users' => $users
        ]);
        echo $view;
    }

    public function create()
    {
        AuthMiddleware::check();

        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $view = new View('admin/users/create', [
            'title' => 'Добавить пользователя'
        ]);
        echo $view;
    }

    public function store()
    {
        AuthMiddleware::check();

        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $role = trim($_POST['role'] ?? 'librarian');
        $full_name = trim($_POST['full_name'] ?? '');

        $errors = [];

        // Валидация
        if (empty($username)) {
            $errors['username'] = 'Логин обязателен';
        } elseif (User::where('username', $username)->exists()) {
            $errors['username'] = 'Пользователь с таким логином уже существует';
        }

        if (empty($password)) {
            $errors['password'] = 'Пароль обязателен';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Пароль должен быть не менее 6 символов';
        } elseif ($password !== $password_confirm) {
            $errors['password_confirm'] = 'Пароли не совпадают';
        }

        if (empty($full_name)) {
            $errors['full_name'] = 'ФИО обязательно';
        }

        if (!empty($errors)) {
            $view = new View('admin/users/create', [
                'title' => 'Добавить пользователя',
                'errors' => $errors,
                'old' => [
                    'username' => $username,
                    'role' => $role,
                    'full_name' => $full_name
                ]
            ]);
            echo $view;
            return;
        }

        // Создаем пользователя
        User::create([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'full_name' => $full_name
        ]);

        $_SESSION['success'] = 'Пользователь успешно добавлен';
        header('Location: /admin/users');
        exit;
    }

    public function edit($id)
    {
        AuthMiddleware::check();

        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $user = User::find($id);

        if (!$user) {
            $_SESSION['error'] = 'Пользователь не найден';
            header('Location: /admin/users');
            exit;
        }

        $view = new View('admin/users/edit', [
            'title' => 'Редактировать пользователя',
            'user' => $user
        ]);
        echo $view;
    }

    public function update()
    {
        AuthMiddleware::check();

        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $user = User::find($id);

        if (!$user) {
            $_SESSION['error'] = 'Пользователь не найден';
            header('Location: /admin/users');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $role = trim($_POST['role'] ?? 'librarian');
        $full_name = trim($_POST['full_name'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $errors = [];

        // Валидация
        if (empty($username)) {
            $errors['username'] = 'Логин обязателен';
        } else {
            $existingUser = User::where('username', $username)->where('id', '!=', $id)->first();
            if ($existingUser) {
                $errors['username'] = 'Пользователь с таким логином уже существует';
            }
        }

        if (empty($full_name)) {
            $errors['full_name'] = 'ФИО обязательно';
        }

        if (!empty($password)) {
            if (strlen($password) < 6) {
                $errors['password'] = 'Пароль должен быть не менее 6 символов';
            } elseif ($password !== $password_confirm) {
                $errors['password_confirm'] = 'Пароли не совпадают';
            }
        }

        if (!empty($errors)) {
            $view = new View('admin/users/edit', [
                'title' => 'Редактировать пользователя',
                'user' => $user,
                'errors' => $errors,
                'old' => [
                    'username' => $username,
                    'role' => $role,
                    'full_name' => $full_name
                ]
            ]);
            echo $view;
            return;
        }

        // Подготавливаем данные для обновления
        $updateData = [
            'username' => $username,
            'role' => $role,
            'full_name' => $full_name
        ];

        // Если указан новый пароль, обновляем его
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $user->update($updateData);

        $_SESSION['success'] = 'Пользователь обновлен';
        header('Location: /admin/users');
        exit;
    }

    public function delete()
    {
        AuthMiddleware::check();

        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);

        // Нельзя удалить самого себя
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Нельзя удалить свою учетную запись';
            header('Location: /admin/users');
            exit;
        }

        $user = User::find($id);

        if (!$user) {
            $_SESSION['error'] = 'Пользователь не найден';
            header('Location: /admin/users');
            exit;
        }

        $user->delete();

        $_SESSION['success'] = 'Пользователь удален';
        header('Location: /admin/users');
        exit;
    }
}