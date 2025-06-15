<h1>Мамины рецепты</h1>

<div class="categories">
    <?php foreach ($categories as $cat): ?>
        <div class="category-box">
            <!-- Ссылка ведет на страницу контроллера RecipeController с параметром category -->
            <a href="/recipes?category=<?= urlencode($cat) ?>">
                <?= htmlspecialchars($cat) ?>
            </a>
        </div>
    <?php endforeach; ?>
</div>
