<?php
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Auth.php';

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require_once __DIR__ . '/../' . $class . '.php';
});

// Инициализация подключения к базе данных SQLite
$db = new \PDO('sqlite:' . __DIR__ . '/../data/database.sqlite');
$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

// Передаём объект PDO в класс Auth для использования в методе attempt()
\app\core\Auth::initDb($db);

// Запускаем сессию (если ещё не запущена)
\app\core\Auth::start();

use app\core\Router;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // убираем GET параметры
$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();

// Подключаем файл с маршрутами (обязательно создайте routes.php в корне проекта)
require_once __DIR__ . '/../routes.php';

// Обработка нестандартных URL с параметрами
if ($method === 'GET' && preg_match('#^/recipes/([^/]+)$#u', $uri, $matches)) {
    $category = urldecode($matches[1]);
    (new \app\controllers\RecipeController($db))->list($category);
    exit;
}

if ($method === 'GET' && preg_match('#^/recipe/(\d+)$#', $uri, $matches)) {
    $id = (int)$matches[1];
    (new \app\controllers\RecipeController($db))->view($id);
    exit;
}

// Обработка всех остальных маршрутов
$router->dispatch($method, $uri);
