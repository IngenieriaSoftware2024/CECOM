<?php

namespace Controllers;

use MVC\Router;

class ReportesController {
    public static function index(Router $router){
        $router->render('reportes/index', []);
    }

}