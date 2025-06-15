<?php
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$db = new PDO('sqlite:' . $dataDir . '/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Таблица пользователей
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'user'
)");

// Таблица категорий
$db->exec("CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
)");

// Таблица рецептов
$db->exec("CREATE TABLE IF NOT EXISTS recipes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    category_id INTEGER,
    cooking_time TEXT,
    difficulty TEXT,
    ingredients TEXT,
    instructions TEXT,
    image TEXT,
    user_id INTEGER,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)");

$categories = ['Завтрак', 'Обед', 'Ужин', 'Полдник', 'Десерт', 'Перекус'];

$db->beginTransaction();
$stmtCat = $db->prepare("INSERT OR IGNORE INTO categories (name) VALUES (?)");
foreach ($categories as $cat) {
    $stmtCat->execute([$cat]);
}
$db->commit();

echo "База данных и таблицы успешно созданы!\n";

$stmtRecipe = $db->prepare("
    INSERT INTO recipes (title, category_id, cooking_time, difficulty, ingredients, instructions, image, user_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

function generateRecipesForCategory($categoryName, $categoryId) {
    $recipes = [];
    $difficulties = ["Легко", "Средне", "Сложно"];
    for ($i = 1; $i <= 10; $i++) {
        $recipes[] = [
            "title" => "$categoryName рецепт $i",
            "cooking_time" => rand(5, 60) . " минут",
            "difficulty" => $difficulties[array_rand($difficulties)],
            "ingredients" => "Ингредиенты для $categoryName рецепта $i",
            "instructions" => "Инструкция по приготовлению $categoryName рецепта $i",
            "image" => null,
            "user_id" => null
        ];
    }
    return $recipes;
}

$db->beginTransaction();

$stmtSelectCat = $db->prepare("SELECT id FROM categories WHERE name = ?");
foreach ($categories as $categoryName) {
    $stmtSelectCat->execute([$categoryName]);
    $categoryId = $stmtSelectCat->fetchColumn();

    $recipes = generateRecipesForCategory($categoryName, $categoryId);

    foreach ($recipes as $recipe) {
        $stmtRecipe->execute([
            $recipe['title'],
            $categoryId,
            $recipe['cooking_time'],
            $recipe['difficulty'],
            $recipe['ingredients'],
            $recipe['instructions'],
            $recipe['image'],
            $recipe['user_id']
        ]);
    }
}

$db->commit();

echo "Добавлено по 10 рецептов для каждой категории.\n";
