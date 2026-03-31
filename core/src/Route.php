<?php

namespace Src;

use Error;

class Route
{
    private static array $routes = [];
    private static array $routesWithParams = [];
    private static string $prefix = '';

    public static function setPrefix($value)
    {
        self::$prefix = $value;
    }

    public static function add(string $route, $action, string $method = 'GET'): void
    {
        // Сохраняем маршрут с методом
        $key = $method . ':' . $route;

        // Проверяем, есть ли параметры в маршруте
        if (strpos($route, '{') !== false) {
            // Конвертируем маршрут с параметрами в регулярное выражение
            $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            self::$routesWithParams[$key] = [
                'pattern' => $pattern,
                'action' => $action,
                'original' => $route
            ];
        } else {
            // Обычный маршрут без параметров
            if (!array_key_exists($key, self::$routes)) {
                self::$routes[$key] = $action;
            }
        }
    }

    public function start(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];

        // Убираем префикс если есть
        if (!empty(self::$prefix)) {
            $path = substr($path, strlen(self::$prefix));
        }

        // Если путь пустой, делаем его '/'
        if (empty($path)) {
            $path = '/';
        }

        $key = $method . ':' . $path;

        // Сначала ищем точное совпадение
        if (array_key_exists($key, self::$routes)) {
            $this->executeAction(self::$routes[$key]);
            return;
        }

        // Ищем маршрут с параметрами
        foreach (self::$routesWithParams as $routeKey => $routeData) {
            // Проверяем метод
            $routeMethod = explode(':', $routeKey)[0];
            if ($routeMethod !== $method) {
                continue;
            }

            // Проверяем соответствие пути
            if (preg_match($routeData['pattern'], $path, $matches)) {
                // Извлекаем параметры
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                $this->executeAction($routeData['action'], $params);
                return;
            }
        }

        throw new Error('This path does not exist: ' . $path);
    }

    private function executeAction($action, array $params = []): void
    {
        // Если это замыкание (анонимная функция)
        if ($action instanceof \Closure) {
            echo $action(...array_values($params));
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

            $controller = new $class();
            call_user_func([$controller, $method], ...array_values($params));
            return;
        }

        // Если это массив [класс, метод]
        if (is_array($action) && count($action) == 2) {
            $class = $action[0];
            $method = $action[1];

            // Если класс указан без namespace, добавляем App\Controllers
            if (is_string($class) && strpos($class, '\\') === false) {
                $class = 'App\\Controllers\\' . $class;
            }

            if (!class_exists($class)) {
                throw new Error('Class does not exist: ' . $class);
            }

            if (!method_exists($class, $method)) {
                throw new Error('Method does not exist: ' . $method);
            }

            $controller = new $class();
            call_user_func([$controller, $method], ...array_values($params));
            return;
        }

        throw new Error('Invalid route action');
    }

    // Метод для получения всех маршрутов (может пригодиться)
    public static function getRoutes(): array
    {
        return array_merge(
            array_keys(self::$routes),
            array_map(function($item) {
                return $item['original'];
            }, self::$routesWithParams)
        );
    }

    // Метод для проверки существования маршрута
    public static function hasRoute(string $route, string $method = 'GET'): bool
    {
        $key = $method . ':' . $route;

        if (array_key_exists($key, self::$routes)) {
            return true;
        }

        foreach (self::$routesWithParams as $routeKey => $routeData) {
            if ($routeData['original'] === $route) {
                return true;
            }
        }

        return false;
    }
}