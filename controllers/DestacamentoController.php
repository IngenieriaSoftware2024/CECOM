<?php

namespace Controllers;

use Exception;
use Model\Destacamentos;
use MVC\Router;

class DestacamentoController
{
    public static function index(Router $router)
    {

        isAuth();
        hasPermission(['CECOM_ADMINISTR', 'CECOM_USUARIO']);
        
        $catalogo = $_SESSION['auth_user'];
        $dependencia = Destacamentos::Dependencia($catalogo);

        $router->render('destacamentos/index', [
            'dependencia' => $dependencia
        ]);
    }


    public static function BuscarDestacamentoAPI()
    {
        try {

            $dependencia = $_SESSION['dep_llave'];

            $data = Destacamentos::BuscarDestacamentos($dependencia);
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

    public static function GuardarDestacamentoAPI()
    {

        $_POST['ubi_dependencia'] = $_SESSION['dep_llave'];
        $_POST['ubi_nombre'] = utf8_decode(htmlspecialchars(trim(mb_strtoupper($_POST['ubi_nombre'], 'UTF-8'))));
        $_POST['ubi_latitud'] = filter_var($_POST['ubi_latitud'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $_POST['ubi_longitud'] = filter_var($_POST['ubi_longitud'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        try {
            $destacamento = new Destacamentos($_POST);
            $data = $destacamento->crear();
            http_response_code(200);
            echo json_encode([
                'codigo' => 2,
                'mensaje' => 'Destacamento Guardado Exitosamente'
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

    public static function ModificarDestacamentoAPI()
    {
        $id = filter_var($_POST['ubi_id'], FILTER_SANITIZE_NUMBER_INT);
        $_POST['ubi_dependencia'] = $_SESSION['dep_llave'];
        $_POST['ubi_nombre'] = utf8_decode(htmlspecialchars(trim(mb_strtoupper($_POST['ubi_nombre'], 'UTF-8'))));
        $_POST['ubi_latitud'] = filter_var($_POST['ubi_latitud'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $_POST['ubi_longitud'] = filter_var($_POST['ubi_longitud'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        try {
            $data = Destacamentos::find($id);
            $data->sincronizar($_POST);
            $data->actualizar();
            http_response_code(200);
            echo json_encode([
                'codigo' => 3,
                'mensaje' => 'Destacamento Modificado Exitosamente'
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

    
    public static function EliminarDestacamentoAPI()
    {

        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        try {

            $eliminar = Destacamentos::find($id);
            $eliminar->sincronizar([
                'ubi_situacion' => 0
            ]);
            
            $eliminar->actualizar();
            http_response_code(200);
            echo json_encode([
                'codigo' => 4,
                'mensaje' => 'Destacamento/Ubicación eliminado exitosamente',
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el Destacamento/Ubicación',
                'detalle' => $e->getMessage(),
            ]);
        }
    }


}
