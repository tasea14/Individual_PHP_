<h1><?= htmlspecialchars($recipe['title']) ?></h1>
<p><strong>Категория:</strong> <?= htmlspecialchars($recipe['category_name']) ?></p>
<p><strong>Время приготовления:</strong> <?= htmlspecialchars($recipe['cooking_time']) ?></p>
<p><strong>Сложность:</strong> <?= htmlspecialchars($recipe['difficulty']) ?></p>

<h2>Ингредиенты</h2>
<p><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>

<h2>Инструкции</h2>
<p><?= nl2br(htmlspecialchars($recipe['instructions'])) ?></p>

<?php if ($recipe['image']): ?>
    <img src="/images/<?= htmlspecialchars($recipe['image']) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" style="max-width: 400px;" />
<?php endif; ?>

<p><a href="/recipes/<?= urlencode($recipe['category_name']) ?>">Назад к списку</a></p>
