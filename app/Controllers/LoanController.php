<?php

namespace App\Controllers;

use App\Auth;
use App\Models\Book;
use App\Models\Reader;
use App\Models\BookLoan;
use Src\View;

class LoanController
{
    public function index()
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        
        // Активные выдачи (книги на руках)
        $activeLoans = BookLoan::activeLoans()
            ->with(['book', 'reader'])
            ->orderBy('issued_at', 'desc')
            ->get();
        
        $view = new View('loans/index', [
            'title' => 'Активные выдачи',
            'loans' => $activeLoans
        ]);
        echo $view;
    }
    
    public function history()
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        
        // История всех выдач (последние 50)
        $history = BookLoan::with(['book', 'reader'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
        
        $view = new View('loans/history', [
            'title' => 'История выдач',
            'loans' => $history
        ]);
        echo $view;
    }
    
    public function create()
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        
        $books = Book::where('available_copies', '>', 0)
            ->orderBy('title')
            ->get();
        
        $readers = Reader::all();
        
        $view = new View('loans/create', [
            'title' => 'Выдать книгу',
            'books' => $books,
            'readers' => $readers
        ]);
        echo $view;
    }
    
    public function store()
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        
        $book_id = (int)($_POST['book_id'] ?? 0);
        $reader_id = (int)($_POST['reader_id'] ?? 0);
        
        $errors = [];
        
        // Проверяем существование книги
        $book = Book::find($book_id);
        if (!$book) {
            $errors[] = 'Книга не найдена';
        } elseif ($book->available_copies <= 0) {
            $errors[] = 'Нет доступных экземпляров книги';
        }
        
        // Проверяем существование читателя
        $reader = Reader::find($reader_id);
        if (!$reader) {
            $errors[] = 'Читатель не найден';
        }
        
        if (!empty($errors)) {
            $books = Book::where('available_copies', '>', 0)->get();
            $readers = Reader::all();
            
            $view = new View('loans/create', [
                'title' => 'Выдать книгу',
                'books' => $books,
                'readers' => $readers,
                'errors' => $errors,
                'old' => $_POST
            ]);
            echo $view;
            return;
        }
        
        // Создаем запись о выдаче
        BookLoan::create([
            'book_id' => $book_id,
            'reader_id' => $reader_id,
            'issued_at' => date('Y-m-d H:i:s')
        ]);
        
        // Уменьшаем количество доступных экземпляров
        $book->available_copies--;
        $book->save();
        
        $_SESSION['success'] = "Книга \"{$book->title}\" выдана читателю {$reader->full_name}";
        header('Location: /loans');
        exit;
    }
    
    public function returnBook()
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        
        $loan_id = (int)($_POST['loan_id'] ?? 0);
        $loan = BookLoan::find($loan_id);
        
        if (!$loan) {
            $_SESSION['error'] = 'Выдача не найдена';
            header('Location: /loans');
            exit;
        }
        
        // Проверяем, не возвращена ли уже книга
        if ($loan->isReturned()) {
            $_SESSION['error'] = 'Книга уже возвращена';
            header('Location: /loans');
            exit;
        }
        
        $book = $loan->book;
        $reader = $loan->reader;
        
        // Отмечаем возврат
        $loan->returned_at = date('Y-m-d H:i:s');
        $loan->save();
        
        // Увеличиваем количество доступных экземпляров
        $book->available_copies++;
        $book->save();
        
        $_SESSION['success'] = "Книга \"{$book->title}\" возвращена читателем {$reader->full_name}";
        header('Location: /loans');
        exit;
    }
}
