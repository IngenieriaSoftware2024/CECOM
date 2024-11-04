<?php

namespace Controllers;

use MVC\Router;

class InicioController {
    public static function index(Router $router){
        $router->render('inicio/index', []);
    }

}