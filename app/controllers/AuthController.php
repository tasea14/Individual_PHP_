<?php
namespace app\controllers;

use app\core\View;
use app\core\Auth;

class AuthController
{
    // Показываем форму входа
    public function showLoginForm()
    {
        View::render('login');
    }

    // Обрабатываем POST-запрос входа
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($username, $password)) {
            // Если есть сохранённый редирект, отправляем туда
            if (!empty($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit;
            }

            // Иначе на главную
            header('Location: /');
            exit;
        } else {
            View::render('login', [
                'error' => 'Неверный логин или пароль',
                'username' => htmlspecialchars($username)
            ]);
        }
    }

    // Показываем форму регистрации
    public function showRegisterForm()
    {
        View::render('register');
    }

    // Обрабатываем POST-запрос регистрации
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if ($username === '' || $password === '') {
            View::render('register', [
                'error' => 'Заполните все поля',
                'username' => htmlspecialchars($username)
            ]);
            return;
        }

        if ($password !== $password_confirm) {
            View::render('register', [
                'error' => 'Пароли не совпадают',
                'username' => htmlspecialchars($username)
            ]);
            return;
        }

        if (Auth::register($username, $password)) {
            header('Location: /login');
            exit;
        } else {
            View::render('register', [
                'error' => 'Пользователь уже существует',
                'username' => htmlspecialchars($username)
            ]);
        }
    }

    // Выход из системы
    public function logout()
    {
        Auth::logout();
        header('Location: /');
        exit;
    }
}
