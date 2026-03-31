<?php

namespace App\Middleware;

use App\Auth;

class AuthMiddleware
{
    /**
     * Проверка авторизации
     */
    public static function check()
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        return true;
    }
    
    /**
     * Проверка, что пользователь - администратор
     */
    public static function admin()
    {
        self::check(); // сначала проверяем авторизацию
        
        if (!Auth::isAdmin()) {
            http_response_code(403);
            die('Доступ запрещен. Требуются права администратора.');
        }
        return true;
    }
    
    /**
     * Проверка, что пользователь - библиотекарь
     */
    public static function librarian()
    {
        self::check(); // сначала проверяем авторизацию
        
        if (!Auth::isLibrarian() && !Auth::isAdmin()) {
            http_response_code(403);
            die('Доступ запрещен. Требуются права библиотекаря.');
        }
        return true;
    }
    
    /**
     * Проверка, что пользователь имеет одну из разрешенных ролей
     */
    public static function role($roles = [])
    {
        self::check();
        
        $userRole = $_SESSION['user_role'] ?? null;
        
        if (!in_array($userRole, $roles)) {
            http_response_code(403);
            die('Доступ запрещен. Недостаточно прав.');
        }
        return true;
    }
}
