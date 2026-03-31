<?php

namespace App\Controllers;

use App\Auth;
use App\Models\Reader;
use App\Middleware\AuthMiddleware;
use Src\View;

class ReaderController
{
    public function index()
    {
        // Используем middleware для проверки авторизации
        AuthMiddleware::check();

        // Получаем всех читателей
        $readers = Reader::all();

        $view = new View('readers/index', [
            'title' => 'Управление читателями',
            'readers' => $readers
        ]);
        echo $view;
    }

    public function create()
    {
        AuthMiddleware::check();

        $view = new View('readers/create', [
            'title' => 'Добавить читателя'
        ]);
        echo $view;
    }

    public function store()
    {
        AuthMiddleware::check();

        // Получаем данные из POST
        $library_card_number = trim($_POST['library_card_number'] ?? '');
        $full_name = trim($_POST['full_name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $errors = [];

        // Валидация
        if (empty($library_card_number)) {
            $errors['library_card_number'] = 'Номер читательского билета обязателен';
        } elseif (Reader::where('library_card_number', $library_card_number)->exists()) {
            $errors['library_card_number'] = 'Читатель с таким номером билета уже существует';
        }

        if (empty($full_name)) {
            $errors['full_name'] = 'ФИО обязательно';
        }

        if (!empty($phone) && !preg_match('/^[\d\s\+\(\)\-]{10,20}$/', $phone)) {
            $errors['phone'] = 'Некорректный номер телефона';
        }

        // Если есть ошибки, возвращаем форму с ошибками
        if (!empty($errors)) {
            $view = new View('readers/create', [
                'title' => 'Добавить читателя',
                'errors' => $errors,
                'old' => [
                    'library_card_number' => $library_card_number,
                    'full_name' => $full_name,
                    'address' => $address,
                    'phone' => $phone
                ]
            ]);
            echo $view;
            return;
        }

        // Создаем читателя
        Reader::create([
            'library_card_number' => $library_card_number,
            'full_name' => $full_name,
            'address' => $address ?: null,
            'phone' => $phone ?: null
        ]);

        $_SESSION['success'] = 'Читатель успешно добавлен';
        header('Location: /readers');
        exit;
    }

    public function edit($id)
    {
        AuthMiddleware::check();

        $reader = Reader::find($id);

        if (!$reader) {
            $_SESSION['error'] = 'Читатель не найден';
            header('Location: /readers');
            exit;
        }

        $view = new View('readers/edit', [
            'title' => 'Редактировать читателя',
            'reader' => $reader
        ]);
        echo $view;
    }

    public function update()
    {
        AuthMiddleware::check();

        $id = (int)($_POST['id'] ?? 0);
        $reader = Reader::find($id);

        if (!$reader) {
            $_SESSION['error'] = 'Читатель не найден';
            header('Location: /readers');
            exit;
        }

        // Получаем данные из POST
        $library_card_number = trim($_POST['library_card_number'] ?? '');
        $full_name = trim($_POST['full_name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $errors = [];

        // Валидация
        if (empty($library_card_number)) {
            $errors['library_card_number'] = 'Номер читательского билета обязателен';
        } else {
            $existingReader = Reader::where('library_card_number', $library_card_number)->first();
            if ($existingReader && $existingReader->id !== $reader->id) {
                $errors['library_card_number'] = 'Читатель с таким номером билета уже существует';
            }
        }

        if (empty($full_name)) {
            $errors['full_name'] = 'ФИО обязательно';
        }

        if (!empty($phone) && !preg_match('/^[\d\s\+\(\)\-]{10,20}$/', $phone)) {
            $errors['phone'] = 'Некорректный номер телефона';
        }

        // Если есть ошибки, возвращаем форму с ошибками
        if (!empty($errors)) {
            $view = new View('readers/edit', [
                'title' => 'Редактировать читателя',
                'reader' => $reader,
                'errors' => $errors,
                'old' => [
                    'library_card_number' => $library_card_number,
                    'full_name' => $full_name,
                    'address' => $address,
                    'phone' => $phone
                ]
            ]);
            echo $view;
            return;
        }

        // Обновляем читателя
        $reader->update([
            'library_card_number' => $library_card_number,
            'full_name' => $full_name,
            'address' => $address ?: null,
            'phone' => $phone ?: null
        ]);

        $_SESSION['success'] = 'Данные читателя обновлены';
        header('Location: /readers');
        exit;
    }

    public function delete()
    {
        AuthMiddleware::check();

        $id = (int)($_POST['id'] ?? 0);
        $reader = Reader::find($id);

        if (!$reader) {
            $_SESSION['error'] = 'Читатель не найден';
            header('Location: /readers');
            exit;
        }

        // Проверяем, есть ли у читателя книги на руках
        $activeLoans = $reader->activeLoans()->count();

        if ($activeLoans > 0) {
            $_SESSION['error'] = 'Нельзя удалить читателя, у которого есть книги на руках';
            header('Location: /readers');
            exit;
        }

        $reader->delete();

        $_SESSION['success'] = 'Читатель удален';
        header('Location: /readers');
        exit;
    }

    public function show($id)
    {
        AuthMiddleware::check();

        $reader = Reader::with(['loans' => function($query) {
            $query->with('book')->orderBy('created_at', 'desc');
        }])->find($id);

        if (!$reader) {
            $_SESSION['error'] = 'Читатель не найден';
            header('Location: /readers');
            exit;
        }

        $view = new View('readers/show', [
            'title' => 'Просмотр читателя',
            'reader' => $reader,
            'active_loans' => $reader->activeLoans()->count()
        ]);
        echo $view;
    }
}