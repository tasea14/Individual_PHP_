<?php
namespace app\models;

use PDO;

class Recipe
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    private function getOrderByDifficulty(): string
    {
        return "CASE difficulty
                    WHEN 'Легко' THEN 1
                    WHEN 'Средне' THEN 2
                    WHEN 'Сложно' THEN 3
                    ELSE 4
                END ASC, recipes.id DESC";
    }

    public function getAllWithLimitOffset(int $limit, int $offset): array
    {
        $orderBy = $this->getOrderByDifficulty();

        $sql = "
            SELECT recipes.*, categories.name AS category_name 
            FROM recipes 
            LEFT JOIN categories ON recipes.category_id = categories.id
            ORDER BY $orderBy
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Метод getByIds: получает рецепты по массиву ID, сохраняет порядок
    public function getByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        // Создаем плейсхолдеры для IN (?,?,?,...)
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        // Создаем CASE для сохранения порядка по ID
        $orderByCase = 'CASE recipes.id ';
        foreach ($ids as $index => $id) {
            $orderByCase .= "WHEN ? THEN $index ";
        }
        $orderByCase .= 'ELSE ' . count($ids) . ' END';

        $sql = "
            SELECT recipes.*, categories.name AS category_name
            FROM recipes
            LEFT JOIN categories ON recipes.category_id = categories.id
            WHERE recipes.id IN ($placeholders)
            ORDER BY $orderByCase
        ";

        $stmt = $this->db->prepare($sql);

        // Связываем параметры для IN (...)
        $i = 1;
        foreach ($ids as $id) {
            $stmt->bindValue($i, $id, PDO::PARAM_INT);
            $i++;
        }
        // Связываем параметры для ORDER BY CASE
        foreach ($ids as $id) {
            $stmt->bindValue($i, $id, PDO::PARAM_INT);
            $i++;
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function getOrderByCategory(): string
    {
        return "CASE categories.name
            WHEN 'Завтрак' THEN 1
            WHEN 'Обед' THEN 2
            WHEN 'Ужин' THEN 3
            WHEN 'Полдник' THEN 4
            WHEN 'Десерт' THEN 5
            WHEN 'Перекус' THEN 6
            ELSE 7
        END";
    }

    public function getByCategoryWithLimitOffset(string $categoryName, int $limit, int $offset): array
    {
        $sql = "
            SELECT recipes.*, categories.name AS category_name 
            FROM recipes
            LEFT JOIN categories ON recipes.category_id = categories.id
            WHERE categories.name = :category
            ORDER BY
                CASE categories.name
                    WHEN 'Завтрак' THEN 1
                    WHEN 'Обед' THEN 2
                    WHEN 'Ужин' THEN 3
                    WHEN 'Полдник' THEN 4
                    WHEN 'Десерт' THEN 5
                    WHEN 'Перекус' THEN 6
                    ELSE 7
                END ASC,
                CASE recipes.difficulty
                    WHEN 'Легко' THEN 1
                    WHEN 'Средне' THEN 2
                    WHEN 'Сложно' THEN 3
                    ELSE 4
                END ASC,
                recipes.id DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':category', $categoryName, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) AS count FROM recipes";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    public function countByCategory(string $categoryName): int
    {
        $sql = "
            SELECT COUNT(*) AS count
            FROM recipes 
            JOIN categories ON recipes.category_id = categories.id
            WHERE categories.name = :category
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':category' => $categoryName]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    public function getById(int $id): ?array
    {
        $sql = "
            SELECT recipes.*, categories.name AS category_name
            FROM recipes
            LEFT JOIN categories ON recipes.category_id = categories.id
            WHERE recipes.id = :id
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
        return $recipe ?: null;
    }

}