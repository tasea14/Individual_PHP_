<?php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/data/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Создаем новую таблицу без email
    $db->exec("
        CREATE TABLE IF NOT EXISTS users_new (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            password TEXT NOT NULL
        )
    ");

    // 2. Копируем данные из старой таблицы
    $db->exec("
        INSERT INTO users_new (id, username, password)
        SELECT id, username, password FROM users
    ");

    // 3. Удаляем старую таблицу
    $db->exec("DROP TABLE users");

    // 4. Переименовываем новую таблицу
    $db->exec("ALTER TABLE users_new RENAME TO users");

    echo "Поле email удалено, таблица users обновлена.";
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
