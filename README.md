# Отчет по Индивидуальной работе по PHP

 ## "Мамины рецепты" — Веб-приложение на PHP (MVC + SQLite)

Проект реализован в рамках лабораторной работы по дисциплине "Веб-программирование на PHP". Цель: освоить принципы MVC-архитектуры, работу с базой SQLite, обработку форм, аутентификацию.



## Содержание :

1. О проекте

2. Инструкция по запуску

3. Описание лабораторной работы

4. Функциональные возможности

5. Сценарии взаимодействия

6. Структура базы данных

7. Примеры использования

8. Контрольные вопросы

9. Источники

## О проекте

Проект “Мамины рецепты” — это веб-приложение для каталога рецептов, разработанное на PHP c использованием MVC-архитектуры, SQLite и сессий для авторизации.

## Инструкция по запуску

1. Разархивируйте архив в папку htdocs (если используете XAMPP).

2. Откройте XAMPP Control Panel, запустите Apache.

3. Перейдите в браузере на http://localhost/.

4. Все! Главная страница отобразит разделы рецептов.

## Описание лабораторной работы

Цель: Разработка простого веб-приложения с MVC-архитектурой, авторизацией, работой с базой данных и обработкой форм.

## Функциональные возможности

1. Авторизация/регистрация

2. Вывод разделов: Завтрак, Обед, Ужин, Полдник, Десерт, Перекус

3. Просмотр рецептов по категориям

4. Добавление/редактирование рецептов (для админа)

5. Сценарии взаимодействия

6. Гость выбирает категорию и читает рецепты

7. Пользователь зарегистрировался и вошел

8. Админ добавляет/редактирует рецепт

9. Структура базы данных (SQLite)

```
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT
);

CREATE TABLE categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT UNIQUE
);

CREATE TABLE recipes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    description TEXT,
    ingredients TEXT,
    instructions TEXT,
    category_id INTEGER,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

```

## Примеры использования

### Пример использования Auth.php для входа:
```
public static function attempt(string $username, string $password): bool
{
    if (self::$db === null) {
        throw new Exception("Database connection is not initialized in Auth class.");
    }

    $stmt = self::$db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        self::login($user);
        return true;
    }

    return false;
}

```
### Router.php — маршрутизация запросов
```
public function get(string $route, $callback)
{
    $this->add('GET', $route, $callback);
}

public function post(string $route, $callback)
{
    $this->add('POST', $route, $callback);
}

```

### Controller.php — базовый контроллер
```
public function render($view, $params = [])
{
    extract($params);
    require __DIR__ . '/../views/layout/header.php';
    require __DIR__ . '/../views/' . $view . '.php';
    require __DIR__ . '/../views/layout/footer.php';
}

```
### Пример шаблона header.php с проверкой авторизации

```
<?php
use app\core\Auth;

if (Auth::check()) {
    $user = Auth::user();
    $username = $user['username'] ?? 'Пользователь';
    echo '<a href="/logout">Выход (' . htmlspecialchars($username) . ')</a>';
} else {
    echo '<a href="/login">Вход</a> <a href="/register">Регистрация</a>';
}
?>

```


## Примеры использования


### Главная страница
### Страница всех рецептов
### Просмотр разделов
### Форма входа

**Все это в папке `images - R` :**

## Ответы на контрольные вопросы

1. `Как реализована архитектура MVC в проекте?`
Контроллеры находятся в app/controllers, модели — в app/models, а представления — в app/views.

2. `Каким образом организовано взаимодействие с базой данных?`
Используется SQLite и объект PDO, подключение происходит в модели Recipe и User.

3. `Как обеспечивается безопасность (валидация, XSS)?`
Все пользовательские данные проходят через htmlspecialchars() перед выводом на страницу.

4. `Какие функции обрабатывают авторизацию?`
В Auth.php реализованы методы login(), logout(), check() и user().

5. `Где происходит маршрутизация?`
В public/index.php происходит подключение Router, который вызывает методы контроллеров.

## Источники

Официальная документация PHP: `https://www.php.net/manual/ru/`

Руководство по PDO: `https://www.php.net/manual/ru/book.pdo.php`

Документация по password_hash и password_verify: `https://www.php.net/manual/ru/function.password-hash.php`

Руководство по MVC на PHP: `https://habr.com/ru/post/221013/`    