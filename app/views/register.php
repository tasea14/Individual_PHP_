<h1>Регистрация</h1>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="/register">
    <label>Логин: <input type="text" name="username" required></label><br>
    <label>Пароль: <input type="password" name="password" required></label><br>
    <label>Повторите пароль: <input type="password" name="password_confirm" required></label><br>
    <button type="submit">Зарегистрироваться</button>
</form>
