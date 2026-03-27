<?php
////
////require 'vendor/autoload.php';
////
////use Illuminate\Database\Capsule\Manager as Capsule;
////use Illuminate\Container\Container;
////use Illuminate\Events\Dispatcher;
////
////// Загружаем конфиг БД
////$dbConfig = require 'config/db.php';
////
////// Инициализируем Eloquent
////$capsule = new Capsule;
////$capsule->addConnection($dbConfig);
////$capsule->setEventDispatcher(new Dispatcher(new Container));
////$capsule->setAsGlobal();
////$capsule->bootEloquent();
////
////use App\Models\Book;
////
////try {
////    $books = Book::all();
////    echo "✅ Успех! Книг найдено: " . $books->count() . PHP_EOL . PHP_EOL;
////
////    if ($books->count() == 0) {
////        echo "⚠️ В базе нет книг. Добавьте тестовые данные.\n";
////    }
////
////    foreach ($books as $book) {
////        echo "📖 {$book->title}" . PHP_EOL;
////        echo "   Автор: {$book->author}" . PHP_EOL;
////        echo "   Год: {$book->year}" . PHP_EOL;
////        echo "   Цена: {$book->price} руб." . PHP_EOL;
////        echo "   Новинка: " . ($book->is_new ? 'Да' : 'Нет') . PHP_EOL;
////        echo "---" . PHP_EOL;
////    }
////
////} catch (\Exception $e) {
////    echo "❌ Ошибка: " . $e->getMessage() . PHP_EOL;
////    echo "Файл: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
////}
//
//
//require 'vendor/autoload.php';
//$app = require 'core/bootstrap.php';
//
//// Запускаем приложение (инициализирует БД)
//$app->run();
//
//use App\Models\User;
//
//try {
//    $user = User::where('username', 'admin')->first();
//
//    if ($user) {
//        echo "✅ Пользователь найден!\n";
//        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
//        echo "Имя: " . $user->full_name . "\n";
//        echo "Логин: " . $user->username . "\n";
//        echo "Роль: " . $user->role . "\n";
//        echo "Пароль 'password': " . ($user->verifyPassword('password') ? "✓ Верный\n" : "✗ Неверный\n");
//        echo "Пароль 'wrong': " . ($user->verifyPassword('wrong') ? "Верный" : "✓ Неверный") . "\n";
//        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
//        echo "\n🎉 Модель User работает корректно!\n";
//    } else {
//        echo "❌ Пользователь admin не найден!\n\n";
//        echo "Проверьте, что:\n";
//        echo "1. Таблица users существует\n";
//        echo "2. В таблице есть запись с username='admin'\n";
//        echo "3. Данные добавлены правильно\n\n";
//
//        // Проверим, есть ли вообще пользователи
//        $count = User::count();
//        echo "Всего пользователей в таблице: " . $count . "\n";
//
//        if ($count > 0) {
//            echo "\nСписок пользователей:\n";
//            foreach (User::all() as $u) {
//                echo "- {$u->username} ({$u->role})\n";
//            }
//        }
//    }
//
//} catch (\Exception $e) {
//    echo "❌ Ошибка: " . $e->getMessage() . "\n";
//    echo "Файл: " . $e->getFile() . ":" . $e->getLine() . "\n";
//}


require 'vendor/autoload.php';
$app = require 'core/bootstrap.php';
$app->run();

use App\Models\Book;
use App\Models\Reader;
use App\Models\BookLoan;
use App\Models\User;

echo "=== Проверка моделей ===\n\n";

// Проверяем Book
$books = Book::count();
echo "✅ Book: {$books} записей\n";

// Проверяем Reader
$readers = Reader::count();
echo "✅ Reader: {$readers} записей\n";

// Проверяем BookLoan
$loans = BookLoan::count();
echo "✅ BookLoan: {$loans} записей\n";

// Проверяем User
$users = User::count();
echo "✅ User: {$users} записей\n";

echo "\n🎉 Все модели работают!\n";