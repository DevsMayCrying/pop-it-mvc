<?php

namespace Src;

use Error;

class Route
{
    private static array $routes = [];
    private static string $prefix = '';

    public static function setPrefix($value)
    {
        self::$prefix = $value;
    }

    public static function add(string $route, $action): void
    {
        if (!array_key_exists($route, self::$routes)) {
            self::$routes[$route] = $action;
        }
    }

    public function start(): void
    {
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];

        // Убираем префикс если есть
        if (!empty(self::$prefix)) {
            $path = substr($path, strlen(self::$prefix));
        }

        // Если путь пустой, делаем его '/'
        if (empty($path)) {
            $path = '/';
        }

        if (!array_key_exists($path, self::$routes)) {
            throw new Error('This path does not exist: ' . $path);
        }

        $action = self::$routes[$path];

        // Если это замыкание (анонимная функция)
        if ($action instanceof \Closure) {
            echo $action();
            return;
        }

        // Если это строка вида 'Controller@method'
        if (is_string($action)) {
            $parts = explode('@', $action);
            $class = 'App\\Controller\\' . $parts[0];
            $method = $parts[1];

            if (!class_exists($class)) {
                throw new Error('Controller class does not exist: ' . $class);
            }

            if (!method_exists($class, $method)) {
                throw new Error('Method does not exist: ' . $method);
            }

            call_user_func([new $class, $method]);
            return;
        }

        // Если это массив [класс, метод]
        if (is_array($action) && count($action) == 2) {
            $class = $action[0];
            $method = $action[1];

            // Если класс указан без namespace, добавляем App\Controller
            if (is_string($class) && strpos($class, '\\') === false) {
                $class = 'App\\Controller\\' . $class;
            }

            if (!class_exists($class)) {
                throw new Error('Class does not exist: ' . $class);
            }

            if (!method_exists($class, $method)) {
                throw new Error('Method does not exist: ' . $method);
            }

            call_user_func([new $class, $method]);
            return;
        }

        throw new Error('Invalid route action');
    }

    // Метод для получения всех маршрутов (может пригодиться)
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    // Метод для проверки существования маршрута
    public static function hasRoute(string $route): bool
    {
        return array_key_exists($route, self::$routes);
    }
}