<h1 class="page-title">
    <?= $category ? 'Рецепты: ' . htmlspecialchars($category) : 'Все рецепты' ?>
</h1>

<?php if (empty($recipes)): ?>
    <p>Рецепты не найдены.</p>
<?php else: ?>
    <div class="recipe-grid">
        <?php foreach ($recipes as $recipe): ?>
            <div class="recipe-card">
                <h2><?= htmlspecialchars($recipe['title']) ?></h2>
                <p><strong>Категория:</strong> <?= htmlspecialchars($recipe['category_name'] ?? '') ?></p>
                
                <?php if (!empty($recipe['cooking_time'])): ?>
                    <p><strong>Время приготовления:</strong> <?= htmlspecialchars($recipe['cooking_time']) ?></p>
                <?php endif; ?>

                <?php if (!empty($recipe['difficulty'])): ?>
                    <p><strong>Сложность:</strong> <?= htmlspecialchars($recipe['difficulty']) ?></p>
                <?php endif; ?>

                <h3>Ингредиенты</h3>
                <p><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>

                <h3>Инструкции</h3>
                <p><?= nl2br(htmlspecialchars($recipe['instructions'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav class="pagination">
            <?php
                $baseUrl = '?';
                if ($category) {
                    $baseUrl .= 'category=' . urlencode($category) . '&';
                }
            ?>

            <?php if ($page > 1): ?>
                <a href="<?= $baseUrl ?>page=<?= $page - 1 ?>">&laquo; Назад</a>
            <?php endif; ?>

            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <?php if ($p == $page): ?>
                    <strong><?= $p ?></strong>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>page=<?= $p ?>"><?= $p ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="<?= $baseUrl ?>page=<?= $page + 1 ?>">Вперёд &raquo;</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>
