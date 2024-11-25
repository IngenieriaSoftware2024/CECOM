<?php

namespace Controllers;

use Exception;
use Model\Marca;
use MVC\Router;

class MarcaController
{
    public static function index(Router $router)
    {
        isAuth();
        hasPermission(['CECOM_ADMINISTR']);

        $router->render('marcas/index', []);
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

    public static function guardarAPI()
    {
        $_POST['mar_id'] = filter_var($_POST['mar_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['mar_descripcion'] = utf8_decode(htmlspecialchars(trim(mb_strtoupper($_POST['mar_descripcion'], 'UTF-8'))));
       
        try {
            $Marca = new Marca($_POST);
            $data = $Marca->crear();
            http_response_code(200);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Marca Registrada Correctamente'
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
        $id = filter_var($_POST['mar_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['mar_descripcion'] = utf8_decode(htmlspecialchars(trim(mb_strtoupper($_POST['mar_descripcion'], 'UTF-8'))));
       
        try {
            $data = Marca::find($id);
            $data->sincronizar($_POST);
            $data->actualizar();
            http_response_code(200);
            echo json_encode([
                'codigo' => 3,
                'mensaje' => 'Marca modificada exitosamente',
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
