<?php
namespace app\core;

class View
{
    public static function render(string $template, array $params = [])
    {
        // Подключаем header
        include __DIR__ . '/../views/layout/header.php';

        // Подключаем шаблон с данными
        include __DIR__ . '/../views/' . $template . '.php';

        // Подключаем footer
        include __DIR__ . '/../views/layout/footer.php';
    }
}
