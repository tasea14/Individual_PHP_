<?php
namespace app\core;

class Controller
{
    public function render($view, $params = [])
    {
        extract($params);
        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layout/footer.php';
    }
}
