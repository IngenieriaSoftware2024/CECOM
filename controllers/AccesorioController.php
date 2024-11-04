<?php

namespace Controllers;

use MVC\Router;

class AccesorioController{
    public static function index(Router $router){
        $router->render('accesorios/index', []);
    }

}