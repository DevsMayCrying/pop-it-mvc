<?php

namespace Src;

use Exception;

class View
{
    private string $view = '';
    private array $data = [];
    private string $viewsPath = '';

    public function __construct(string $view = '', array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
        // Путь к папке views (на два уровня выше core)
        $this->viewsPath = dirname(__DIR__, 2) . '/views';
    }

    public function render(string $view = '', array $data = []): string
    {
        $viewFile = $this->viewsPath . '/' . str_replace('.', '/', $view) . '.php';

        // ОТЛАДКА: Выводим информацию о поиске файла
        $debug = "<!-- ========== DEBUG VIEW ========== -->\n";
        $debug .= "<!-- Запрошенное представление: {$view} -->\n";
        $debug .= "<!-- Полный путь к файлу: {$viewFile} -->\n";
        $debug .= "<!-- Файл существует: " . (file_exists($viewFile) ? "ДА" : "НЕТ") . " -->\n";

        if (!file_exists($viewFile)) {
            $debug .= "<!-- ОШИБКА: Файл не найден! -->\n";
            $debug .= "<!-- ========== END DEBUG ========== -->\n";
            throw new Exception("View file not found: " . $viewFile);
        }

        $debug .= "<!-- Файл найден, загружаем... -->\n";
        $debug .= "<!-- ========== END DEBUG ========== -->\n";

        extract($this->data, EXTR_PREFIX_SAME, '');
        extract($data, EXTR_PREFIX_SAME, '');

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = $this->viewsPath . '/layouts/main.php';

        // ОТЛАДКА: Информация о layout
        $debugLayout = "<!-- ========== DEBUG LAYOUT ========== -->\n";
        $debugLayout .= "<!-- Layout файл: {$layoutFile} -->\n";
        $debugLayout .= "<!-- Layout существует: " . (file_exists($layoutFile) ? "ДА" : "НЕТ") . " -->\n";
        $debugLayout .= "<!-- ========== END DEBUG ========== -->\n";

        if (file_exists($layoutFile)) {
            extract($this->data, EXTR_PREFIX_SAME, '');
            extract($data, EXTR_PREFIX_SAME, '');
            ob_start();
            require $layoutFile;
            return $debugLayout . $debug . ob_get_clean();
        }

        return $debugLayout . $debug . $content;
    }

    public function __toString(): string
    {
        try {
            return $this->render($this->view, $this->data);
        } catch (Exception $e) {
            return "<!-- View Error: " . $e->getMessage() . " -->\n";
        }
    }
}