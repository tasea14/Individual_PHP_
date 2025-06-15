<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Мамины рецепты</title>
    <link rel="stylesheet" href="/css/styles.css" />
</head>
<body>
<header>
    <nav>
        <a href="/">Главная</a> 
        <a href="/recipes">Все рецепты</a>

        <?php
        use app\core\Auth;

        // Проверка, авторизован ли пользователь
        if (Auth::check()) {
            $user = Auth::user();
            $username = 'Пользователь';

            // Получение имени пользователя, если оно доступно
            if (is_array($user)) {
                $username = $user['username'] ?? $username;
            } elseif (is_object($user)) {
                $username = $user->username ?? $username;
            }

            // Убираем ссылку на избранное, оставляем только выход
            echo '<a href="/logout">Выход (' . htmlspecialchars($username) . ')</a>';
        } else {
            echo '<a href="/login">Вход</a>';
            echo '<a href="/register">Регистрация</a>';
        }
        ?>
    </nav>
</header>

<main>
