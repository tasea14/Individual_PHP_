<h1 class="page-title"><?= htmlspecialchars($recipe['title']) ?></h1>

<p><strong>Категория:</strong> <?= htmlspecialchars($recipe['category_name'] ?? '') ?></p>
<p><strong>Время приготовления:</strong> <?= htmlspecialchars($recipe['cooking_time'] ?? '') ?></p>
<p><strong>Сложность:</strong> <?= htmlspecialchars($recipe['difficulty'] ?? '') ?></p>

<h3>Ингредиенты</h3>
<p><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>

<h3>Инструкции</h3>
<p><?= nl2br(htmlspecialchars($recipe['instructions'])) ?></p>

<form action="/favorites/add" method="post">
    <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">
    <button type="submit">Добавить в избранное</button>
</form>
