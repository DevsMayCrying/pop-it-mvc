<?php
//const DIR_CONFIG = '/../config';
//
//spl_autoload_register(function ($className) {
//    $paths = include __DIR__ . DIR_CONFIG . '/path.php';
//
//    // Преобразуем namespace в путь
//    $classPath = str_replace('\\', '/', $className);
//
//    // Специальная обработка для Src классов
//    if (strpos($classPath, 'Src/') === 0) {
//        // Убираем Src/ из начала и ищем в core/src/
//        $fileName = __DIR__ . '/src/' . substr($classPath, 4) . '.php';
//
//        if (file_exists($fileName)) {
//            require_once $fileName;
//            return;
//        }
//    }
//
//    // Для остальных классов
//    foreach ($paths['classes'] as $path) {
//        $fileName = __DIR__ . "/../$path/$classPath.php";
//        if (file_exists($fileName)) {
//            require_once $fileName;
//            return;
//        }
//    }
//
//    throw new Exception("Класс $className не найден");
//});
//
//function getConfigs(string $path = DIR_CONFIG): array
//{
//    $settings = [];
//    foreach (scandir(__DIR__ . $path) as $file) {
//        $name = explode('.', $file)[0];
//        if (!empty($name)) {
//            $settings[$name] = include __DIR__ . "$path/$file";
//        }
//    }
//    return $settings;
//}
//
//require_once __DIR__ . '/../routes/web.php';
//
//return new Src\Application(new Src\Settings(getConfigs()));
//бутстрап переписывался из за некоректноо пространства имен, не фурычило

// Подключение автозагрузчика Composer (ПЕРВАЯ СТРОКА!)
//


//Путь до директории с конфигурационными файлами
const DIR_CONFIG = '/../config';

//Подключение автозагрузчика composer
require_once __DIR__ . '/../vendor/autoload.php';

//Функция, возвращающая массив всех настроек приложения
function getConfigs(string $path = DIR_CONFIG): array
{
    $settings = [];
    foreach (scandir(__DIR__ . $path) as $file) {
        $name = explode('.', $file)[0];
        if (!empty($name)) {
            $settings[$name] = include __DIR__ . "$path/$file";
        }
    }
    return $settings;
}

require_once __DIR__ . '/../routes/web.php';

return new Src\Application(new Src\Settings(getConfigs()));