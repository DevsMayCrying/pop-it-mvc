<?php

namespace App\Controllers;

use App\Models\Book;
use App\Middleware\AuthMiddleware;
use Src\View;

class BookController
{
    public function __construct()
    {
        // Только авторизованные пользователи (библиотекари и админ)
        AuthMiddleware::check();
    }
    
    /**
     * Список всех книг
     */
    public function index()
    {
        $books = Book::orderBy('created_at', 'desc')->get();
        
        $view = new View('books/index', [
            'title' => 'Книги',
            'books' => $books
        ]);
        echo $view;
    }
    
    /**
     * Форма создания книги
     */
    public function create()
    {
        $view = new View('books/create', ['title' => 'Добавить книгу']);
        echo $view;
    }
    
    /**
     * Сохранение новой книги
     */
    public function store()
    {
        // Валидация
        $errors = [];
        
        $author = trim($_POST['author'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $year = (int)($_POST['year'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $is_new = isset($_POST['is_new']) ? 1 : 0;
        $annotation = trim($_POST['annotation'] ?? '');
        
        if (empty($author)) {
            $errors[] = 'Автор обязателен';
        }
        
        if (empty($title)) {
            $errors[] = 'Название обязательно';
        }
        
        if ($year < 1000 || $year > date('Y')) {
            $errors[] = 'Год издания должен быть от 1000 до ' . date('Y');
        }
        
        if ($price < 0) {
            $errors[] = 'Цена не может быть отрицательной';
        }
        
        if (!empty($errors)) {
            session_start();
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: /books/create');
            exit;
        }
        
        // Создаем книгу
        Book::create([
            'author' => $author,
            'title' => $title,
            'year' => $year,
            'price' => $price,
            'is_new' => $is_new,
            'annotation' => $annotation
        ]);
        
        session_start();
        $_SESSION['success'] = 'Книга успешно добавлена';
        header('Location: /books');
        exit;
    }
    
    /**
     * Форма редактирования книги
     */
    public function edit($id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            session_start();
            $_SESSION['error'] = 'Книга не найдена';
            header('Location: /books');
            exit;
        }
        
        $view = new View('books/edit', [
            'title' => 'Редактировать книгу',
            'book' => $book
        ]);
        echo $view;
    }
    
    /**
     * Обновление книги
     */
    public function update($id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            session_start();
            $_SESSION['error'] = 'Книга не найдена';
            header('Location: /books');
            exit;
        }
        
        // Валидация
        $errors = [];
        
        $author = trim($_POST['author'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $year = (int)($_POST['year'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $is_new = isset($_POST['is_new']) ? 1 : 0;
        $annotation = trim($_POST['annotation'] ?? '');
        
        if (empty($author)) {
            $errors[] = 'Автор обязателен';
        }
        
        if (empty($title)) {
            $errors[] = 'Название обязательно';
        }
        
        if ($year < 1000 || $year > date('Y')) {
            $errors[] = 'Год издания должен быть от 1000 до ' . date('Y');
        }
        
        if ($price < 0) {
            $errors[] = 'Цена не может быть отрицательной';
        }
        
        if (!empty($errors)) {
            session_start();
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: /books/edit/$id");
            exit;
        }
        
        // Обновляем книгу
        $book->update([
            'author' => $author,
            'title' => $title,
            'year' => $year,
            'price' => $price,
            'is_new' => $is_new,
            'annotation' => $annotation
        ]);
        
        session_start();
        $_SESSION['success'] = 'Книга успешно обновлена';
        header('Location: /books');
        exit;
    }
    
    /**
     * Удаление книги
     */
    public function delete($id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            session_start();
            $_SESSION['error'] = 'Книга не найдена';
            header('Location: /books');
            exit;
        }
        
        // Проверяем, есть ли активные выдачи
        if ($book->activeLoan()) {
            session_start();
            $_SESSION['error'] = 'Нельзя удалить книгу, которая находится у читателя';
            header('Location: /books');
            exit;
        }
        
        $book->delete();
        
        session_start();
        $_SESSION['success'] = 'Книга успешно удалена';
        header('Location: /books');
        exit;
    }
    
    /**
     * Просмотр одной книги
     */
    public function show($id)
    {
        $book = Book::with('readers')->find($id);
        
        if (!$book) {
            session_start();
            $_SESSION['error'] = 'Книга не найдена';
            header('Location: /books');
            exit;
        }
        
        $view = new View('books/show', [
            'title' => $book->title,
            'book' => $book
        ]);
        echo $view;
    }
}
