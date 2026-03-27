<?php

use Src\Route;

// Маршрут для главной страницы - используем строковый формат
Route::add('/', 'site@index');

// Другие маршруты
Route::add('/about', 'site@about');
Route::add('/post', 'site@post');

// Можно также использовать массив
// Route::add('/contact', ['App\\Controller\\site', 'contact']);

// Тестовый маршрут с замыканием
Route::add('/test', function() {
    return 'Test page works!';
});