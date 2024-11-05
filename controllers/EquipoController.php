<?php

namespace Controllers;

use MVC\Router;

class EquipoController
{
    public static function index(Router $router)
    {
        $router->render('equipos/index', []);
    }
}
