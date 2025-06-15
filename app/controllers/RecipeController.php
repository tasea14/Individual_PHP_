<?php
namespace app\controllers;

use app\core\Controller;
use app\models\Recipe;
use PDO;

class RecipeController extends Controller
{
    protected $recipeModel;

    public function __construct()
    {
        $db = new PDO('sqlite:' . __DIR__ . '/../../data/database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->recipeModel = new Recipe($db);
    }

    public function list()
    {
        $category = isset($_GET['category']) ? trim($_GET['category']) : null;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        if ($category) {
            // Для отладки: лог категории
            error_log("Категория: " . $category);
            
            $totalRecipes = $this->recipeModel->countByCategory($category);
            $recipes = $this->recipeModel->getByCategoryWithLimitOffset($category, $limit, $offset);
        } else {
            $totalRecipes = $this->recipeModel->countAll();
            $recipes = $this->recipeModel->getAllWithLimitOffset($limit, $offset);
        }

        $totalPages = $totalRecipes > 0 ? ceil($totalRecipes / $limit) : 1;

        $this->render('recipe_list', [
            'recipes' => $recipes,
            'category' => $category,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    public function view($id)
    {
        $recipe = $this->recipeModel->getById($id);
        if (!$recipe) {
            http_response_code(404);
            echo "Рецепт не найден";
            return;
        }

        $this->render('recipe_view', ['recipe' => $recipe]);
    }

    public function show()
{
    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo "ID не указан";
        return;
    }

    $db = new \PDO('sqlite:' . __DIR__ . '/../../data/database.sqlite');
    $stmt = $db->prepare("SELECT * FROM recipes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $recipe = $stmt->fetch(\PDO::FETCH_ASSOC);

    if (!$recipe) {
        echo "Рецепт не найден.";
        return;
    }

    include __DIR__ . '/../views/recipes/show.php';
}


}
