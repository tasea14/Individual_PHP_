<?php
namespace app\core;

use PDO;
use Exception;

class Auth
{
    protected static ?PDO $db = null;

    // Инициализация подключения к БД (нужно вызвать перед использованием attempt)
    public static function initDb(PDO $pdo): void
    {
        self::$db = $pdo;
    }

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(array $user): void
    {
        self::start();
        $_SESSION['user'] = $user;
    }

    public static function logout(): void
    {
        self::start();
        unset($_SESSION['user']);
    }

    public static function user(): ?array
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        self::start();
        return isset($_SESSION['user']);
    }

    /**
     * Попытка аутентификации по username и паролю.
     * @param string $username
     * @param string $password
     * @return bool
     * @throws Exception
     */
    public static function attempt(string $username, string $password): bool
    {
        if (self::$db === null) {
            throw new Exception("Database connection is not initialized in Auth class.");
        }

        $stmt = self::$db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            self::login($user);
            return true;
        }

        return false;
    }

    /**
     * Регистрация нового пользователя.
     * @param string $username
     * @param string $password
     * @return bool
     * @throws Exception
     */
    public static function register(string $username, string $password): bool
    {
        if (self::$db === null) {
            throw new Exception("Database connection is not initialized in Auth class.");
        }

        // Проверяем, существует ли уже пользователь с таким именем
        $stmt = self::$db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        if ($stmt->fetchColumn() > 0) {
            return false; // пользователь уже есть
        }

        // Хешируем пароль
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Вставляем нового пользователя
        $stmt = self::$db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        return $stmt->execute([
            ':username' => $username,
            ':password' => $passwordHash,
        ]);
    }
}
