<?php

namespace Controllers;

use Exception;
use Model\Accesorio;
use MVC\Router;

class AccesorioController{
    public static function index(Router $router){
        $router->render('accesorios/index', []);
    }

    public static function buscarAPI()
    {
        try {
            $data = Accesorio::Buscar();
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

    public static function guardarAPI()
    {
        $_POST['acc_id'] = filter_var($_POST['acc_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['acc_nombre'] = utf8_decode(htmlspecialchars(trim(mb_strtoupper($_POST['acc_nombre'], 'UTF-8'))));
        $_POST['acc_desc'] = utf8_decode(htmlspecialchars(trim(mb_strtoupper($_POST['acc_desc'], 'UTF-8'))));
        $_POST['acc_tipo'] = filter_var($_POST['acc_tipo'], FILTER_SANITIZE_NUMBER_INT);

        try {
            $datos = new Accesorio($_POST);
            $data = $datos->crear();
            http_response_code(200);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Accesorio Registrado Correctamente'
            ]);
        } catch (Exception $error) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al realizar el Registro',
                'detalle' => $error->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        $id = filter_var($_POST['acc_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['acc_nombre'] = utf8_decode(htmlspecialchars(trim(mb_strtoupper($_POST['acc_nombre'], 'UTF-8'))));
        $_POST['acc_desc'] = utf8_decode(htmlspecialchars(trim(mb_strtoupper($_POST['acc_desc'], 'UTF-8'))));
        $_POST['acc_tipo'] = filter_var($_POST['acc_tipo'], FILTER_SANITIZE_NUMBER_INT);
       
        try {
            $data = Accesorio::find($id);
            $data->sincronizar($_POST);
            $data->actualizar();
            http_response_code(200);
            echo json_encode([
                'codigo' => 3,
                'mensaje' => 'Accesorio modificada exitosamente',
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}