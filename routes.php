<?php
/** @var \app\core\Router $router */

// Главная страница
$router->add('GET', '/', 'HomeController@index');

// Рецепты
$router->add('GET', '/recipes', 'RecipeController@list');

// Избранное
$router->add('GET', '/favorites', 'FavoritesController@index');  // без /index

$router->add('POST', '/favorites/add', 'FavoritesController@add');
$router->add('POST', '/favorites/remove', 'FavoritesController@remove');

// Аутентификация
$router->add('GET', '/login', 'AuthController@showLoginForm');  // метод для отображения формы
$router->add('POST', '/login', 'AuthController@login');         // метод для обработки входа

$router->add('GET', '/register', 'AuthController@showRegisterForm');  // метод для формы регистрации
$router->add('POST', '/register', 'AuthController@register');         // метод для обработки регистрации

$router->add('GET', '/logout', 'AuthController@logout');

$router->add('GET', '/favorites', 'FavoritesController@index');
