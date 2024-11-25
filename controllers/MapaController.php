<?php

namespace Controllers;

use Exception;
use Model\Destacamentos;
use MVC\Router;

class MapaController
{
    public static function index(Router $router)
    {
        isAuth();
        hasPermission(['CECOM_ADMINISTR', 'CECOM_USUARIO']);
        
        $catalogo = $_SESSION['auth_user'];
        $dependencia = Destacamentos::Dependencia($catalogo);
        $router->render('mapa/index', [
            'dependencia' => $dependencia
        ]);
    }

    public static function DestacamentoActivosAPI()
    {
        try {

            $data = Destacamentos::DestacamentosActivosGeneral();
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

    public static function EquiposPorDestacamentoAPI()
    {
        $id = filter_var($_GET['destacamento'], FILTER_SANITIZE_NUMBER_INT);

        try {

            $data = Destacamentos::EquiposPorDestacamentos($id);
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


    public static function EquipoTipoAPI()
    {
        $tipo = htmlspecialchars(trim(mb_strtoupper(mb_convert_encoding($_GET['tipo'], 'UTF-8'))));

        $ids = [];

        switch ($tipo) {
            case 'RADIOS':
                $ids = [3, 4, 5, 6, 12];
                break;
            case 'ANTENAS':
                $ids = [1];
                break;
            case 'REPETIDORAS':
                $ids = [2];
                break;
            case 'ENLACES':
                $ids = [13];
                break;
            default:

                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Tipo de equipo no válido'
                ]);
                return;
        }

        try {

            $data = Destacamentos::ObtenerUbicacionTipoEquipo($ids);
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
