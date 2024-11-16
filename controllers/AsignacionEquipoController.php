<?php

namespace Controllers;

use Exception;
use Model\AsignacionDependencia;
use Model\Destacamentos;
use MVC\Router;

class AsignacionEquipoController
{
    public static function index(Router $router)
    {

        $dependencias = AsignacionDependencia::Dependencias();

        $router->render('asignaciones/index', [
            'dependencias' => $dependencias
        ]);
    }

    public static function index2(Router $router)
    {
        $dep_llave = $_SESSION['dep_llave'];
        $destacamentos = Destacamentos::DestacamentosDependencia($dep_llave);

        $router->render('asignaciones/administracion', [
            'destacamentos' => $destacamentos
        ]);
    }


    public static function EquiposAlmacenAPI()
    {
        try {
            $dep_llave  = $_SESSION['dep_llave'];
            $data = AsignacionDependencia::EquiposAlmacen($dep_llave);
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

    public static function informacionOficialAPI()
    {
        try {

            $catalogo = $_POST['per_catalogo'];
            $dependencia = $_POST['dependencia'];

            $data = AsignacionDependencia::InformacionOficial($catalogo, $dependencia);
            http_response_code(200);
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al realizar la busqueda',
                'detalle' => $e->getMessage(),
            ]);
        }
    }


    public static function AsignarDependenciaAPI()
    {
        if (isset($_POST['equipos'])) {
            $Equipos_Registrar = json_decode($_POST['equipos'], true);

            try {
                if ($Equipos_Registrar && is_array($Equipos_Registrar)) {

                    $conexion = AsignacionDependencia::getDB();
                    $conexion->beginTransaction();
                    $fecha_actual = date("Y-m-d");

                    $errores = [];

                    foreach ($Equipos_Registrar as $equipos) {

                        $asi_id = filter_var($equipos['asi_id'], FILTER_SANITIZE_NUMBER_INT);
                        $eqp_id = filter_var($equipos['idEquipo'], FILTER_SANITIZE_NUMBER_INT);

                        $equipo = AsignacionDependencia::cambioSituacionEquipo($asi_id, $eqp_id);

                        if ($equipo) {

                            $motivo = utf8_decode(htmlspecialchars(trim(mb_strtoupper($equipos['motivo'], 'UTF-8'))));
                            $asignacion = new AsignacionDependencia([
                                'asi_equipo' => $equipos['idEquipo'],
                                'asi_dependencia' => $equipos['dependencia'],
                                'asi_fecha' => $fecha_actual,
                                'asi_responsable' => $equipos['plaza'],
                                'asi_motivo' => $motivo,
                                'asi_status' => 4,
                                'asi_situacion' => 1
                            ]);

                            $asignar = $asignacion->crear();
                        } else {

                            $errores[] = "Equipo con ID {$equipos['idEquipo']} no encontrado.";
                        }
                    }

                    if (!empty($errores)) {

                        $conexion->rollBack();
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => 'Errores al cambiar la situación de algunos equipos',
                            'detalle' => $errores
                        ]);
                        return;
                    }


                    $conexion->commit();

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Éxito al asignar nuevos equipos'
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'No se recibieron datos válidos.'
                    ]);
                }
            } catch (Exception $e) {

                $conexion->rollBack();
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al realizar el registro',
                    'detalle' => $e->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'No se recibieron datos.'
            ]);
        }
    }

    public static function BuscarTodosAPI()
    {
        try {
            
            $data = AsignacionDependencia::BuscarTodos();
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
