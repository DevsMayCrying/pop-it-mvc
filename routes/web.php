<?php
// routes/web.php

use Src\Route;
use App\Controllers\AuthController;
use App\Controllers\BookController;
use App\Controllers\ReaderController;
use App\Controllers\DashboardController;
use App\Controllers\LoanController; // Правильное имя контроллера

// Маршруты для аутентификации
Route::add('/login', [AuthController::class, 'showLogin'], 'GET');
Route::add('/login', [AuthController::class, 'login'], 'POST');
Route::add('/logout', [AuthController::class, 'logout'], 'GET');

// Маршрут для дашборда
Route::add('/dashboard', [DashboardController::class, 'index'], 'GET');
Route::add('/', [DashboardController::class, 'index'], 'GET');

// Маршруты для книг
Route::add('/books', [BookController::class, 'index'], 'GET');
Route::add('/books/create', [BookController::class, 'create'], 'GET');
Route::add('/books/store', [BookController::class, 'store'], 'POST');
Route::add('/books/edit/{id}', [BookController::class, 'edit'], 'GET');
Route::add('/books/update', [BookController::class, 'update'], 'POST');
Route::add('/books/delete', [BookController::class, 'delete'], 'POST');

// Маршруты для читателей
Route::add('/readers', [ReaderController::class, 'index'], 'GET');
Route::add('/readers/create', [ReaderController::class, 'create'], 'GET');
Route::add('/readers/store', [ReaderController::class, 'store'], 'POST');
Route::add('/readers/edit/{id}', [ReaderController::class, 'edit'], 'GET');
Route::add('/readers/update', [ReaderController::class, 'update'], 'POST');
Route::add('/readers/delete', [ReaderController::class, 'delete'], 'POST');

// Маршруты для выдач книг (используем LoanController)
Route::add('/loans', [LoanController::class, 'index'], 'GET');
Route::add('/loans/history', [LoanController::class, 'history'], 'GET');
Route::add('/loans/create', [LoanController::class, 'create'], 'GET');
Route::add('/loans/store', [LoanController::class, 'store'], 'POST');
Route::add('/loans/return', [LoanController::class, 'returnBook'], 'POST');

// Маршруты для админ-панели (управление пользователями/Библиотекарями )
Route::add('/admin/users', [\App\Controllers\Admin\UserController::class, 'index'], 'GET');
Route::add('/admin/users/create', [\App\Controllers\Admin\UserController::class, 'create'], 'GET');
Route::add('/admin/users/store', [\App\Controllers\Admin\UserController::class, 'store'], 'POST');
Route::add('/admin/users/edit/{id}', [\App\Controllers\Admin\UserController::class, 'edit'], 'GET');
Route::add('/admin/users/update', [\App\Controllers\Admin\UserController::class, 'update'], 'POST');
Route::add('/admin/users/delete', [\App\Controllers\Admin\UserController::class, 'delete'], 'POST');