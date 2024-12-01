<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;

class InicioController {
    public static function index(Router $router){
        isAuth();
        hasPermission(['CECOM_ADMINISTR', 'CECOM_USUARIO']);

        $user = $_SESSION['auth_user'];
        $userInfo = ActiveRecord::fetchArray("SELECT * from mper inner join morg on per_plaza = org_plaza inner join mdep on org_dependencia = dep_llave inner join grados on per_grado = gra_codigo where per_catalogo = $user ")[0];
        $_SESSION['user_info'] = $userInfo;
        $router->render('inicio/index', [
            'user' => $userInfo
        ]);
    }

    public static function AyudaIndex(Router $router){
        
        hasPermission(['CECOM_ADMINISTR', 'CECOM_USUARIO']);

        $router->render('ayuda/ayuda', []);
    }

}