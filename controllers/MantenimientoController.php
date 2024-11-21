<?php

namespace Controllers;

use MVC\Router;

class MantenimientoController {
    public static function index(Router $router){
        $router->render('mantenimientos/mantenimiento', []);
    }

    public static function buscarAPI()
    {
        try {
            $marcas = new Marca();
            $data = $marcas->all();
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => "Datos encontrados correctamente",
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al realizar la busqueda',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

}