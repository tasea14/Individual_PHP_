<?php
namespace app\controllers;

use app\core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $categories = ['Завтрак', 'Обед', 'Ужин', 'Полдник', 'Десерт', 'Перекус'];

        $this->render('home', [
            'categories' => $categories,
        ]);
    }
}
