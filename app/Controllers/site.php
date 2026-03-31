<?php

namespace App\Controller;

class site
{
    public function index()
    {
        echo 'Главная страница работает!';
    }

    public function about()
    {
        echo 'Страница "О нас"';
    }

    public function post()
    {
        echo 'Страница поста';
    }

    public function contact()
    {
        echo 'Страница контактов';
    }
}