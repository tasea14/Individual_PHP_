<h1>Вход</h1>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="/login">
    <label>Логин: <input type="text" name="username" required value="<?= htmlspecialchars($username ?? '') ?>"></label><br>
    <label>Пароль: <input type="password" name="password" required></label><br>
    <button type="submit">Войти</button>
</form>
