<?php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/data/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Создаем таблицу categories
    $db->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE
        )
    ");

    // Вставляем категории, если их нет
    $categories = ['Завтрак', 'Обед', 'Ужин', 'Полдник', 'Десерт', 'Перекус'];
    foreach ($categories as $cat) {
        $stmt = $db->prepare("INSERT OR IGNORE INTO categories (name) VALUES (:name)");
        $stmt->execute([':name' => $cat]);
    }

    echo "Таблица categories создана и заполнена.";
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
