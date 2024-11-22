<?php

namespace Controllers;

use Exception;
use Model\AsignacionAccesorio;
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
        $catalogo = $_SESSION['auth_user'];
        $dependencia = Destacamentos::Dependencia($catalogo);
        $destacamentos = Destacamentos::DestacamentosDependencia($dep_llave);

        $router->render('asignaciones/administracion', [
            'destacamentos' => $destacamentos,
            'dependencia' => $dependencia
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

            $dependencia = isset($_POST['dependencia']) && !empty($_POST['dependencia']) ? $_POST['dependencia'] : $_SESSION['dep_llave'];
            $catalogo = $_POST['per_catalogo'];


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
        if (!isset($_POST['equipos'])) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'No se recibieron datos.'
            ]);
            return;
        }

        $Equipos_Registrar = json_decode($_POST['equipos'], true);

        try {
            if ($Equipos_Registrar && is_array($Equipos_Registrar)) {

                $conexion = AsignacionDependencia::getDB();
                $conexion->beginTransaction();

                $fecha_actual = date("Y-m-d");


                foreach ($Equipos_Registrar as $equipo) {

                    if (!isset($equipo['asi_id'], $equipo['idEquipo'], $equipo['dependencia'], $equipo['plaza'])) {

                        $conexion->rollBack();
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => 'Datos incompletos para uno o más equipos'
                        ]);
                        return;
                    }

                    $asi_id = filter_var($equipo['asi_id'], FILTER_SANITIZE_NUMBER_INT);
                    $eqp_id = filter_var($equipo['idEquipo'], FILTER_SANITIZE_NUMBER_INT);

                    if (!AsignacionDependencia::cambioSituacionEquipo($asi_id, $eqp_id)) {
                        $conexion->rollBack();
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => "Equipo con ID {$eqp_id} no encontrado."
                        ]);
                        return;
                    }
                }


                foreach ($Equipos_Registrar as $equipos) {
                    $asi_id = filter_var($equipos['asi_id'], FILTER_SANITIZE_NUMBER_INT);
                    $eqp_id = filter_var($equipos['idEquipo'], FILTER_SANITIZE_NUMBER_INT);

                    $dependencia = $equipos['dependencia'];

                    if ($dependencia === 'BAJA') {

                        $dependencia = "";
                        $catalogo = $_SESSION['auth_user'];
                        $responsable = AsignacionDependencia::ResponsableEnviarMantenimiento($catalogo);

                        $motivo = htmlspecialchars(trim(mb_strtoupper(
                            mb_convert_encoding(
                                "Eliminado por: {$responsable['grado_arma']} {$responsable['nombre_completo']}. Motivo: {$equipos['motivo']}",
                                'UTF-8'
                            )
                        )));

                        $status = 7;
                        $situacion = 0;
                    } else {
                        $motivo = htmlspecialchars(trim(mb_strtoupper(mb_convert_encoding($equipos['motivo'], 'UTF-8'))));
                        $status = 4;
                        $situacion = 1;
                    }

                    $asignacion = new AsignacionDependencia([
                        'asi_equipo' => $equipos['idEquipo'],
                        'asi_dependencia' => $dependencia,
                        'asi_destacamento' => '',
                        'asi_fecha' => $fecha_actual,
                        'asi_responsable' => $equipos['plaza'],
                        'asi_motivo' => $motivo,
                        'asi_status' => $status,
                        'asi_situacion' => $situacion
                    ]);

                    if (!$asignacion->crear()) {
                        $conexion->rollBack();
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => "Error al asignar el equipo {$eqp_id}"
                        ]);
                        return;
                    }
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
            if (isset($conexion)) {
                $conexion->rollBack();
            }
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al realizar el registro',
                'detalle' => $e->getMessage()
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

    public static function EquiposDependenciaAPI()
    {
        try {

            $dep_llave = $_SESSION['dep_llave'];
            $data = AsignacionDependencia::EquiposDependencias($dep_llave);
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

    public static function DatosUsuarioAPI()
    {
        try {

            $catalogo = $_SESSION['auth_user'];
            $dependencia = $_SESSION['dep_llave'];

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

    public static function AsignarDestinoAPI()
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


                            $destino = $equipos['destino'];


                            if ($destino === 'mantenimiento') {
                                $destino = 1;
                                $asi_status = 8;

                                $catalogo = $_SESSION['auth_user'];
                                $responsable = AsignacionDependencia::ResponsableEnviarMantenimiento($catalogo);

                                $motivo = htmlspecialchars(trim(mb_strtoupper(
                                    mb_convert_encoding(
                                        "Enviado por: {$responsable['grado_arma']} {$responsable['nombre_completo']}. Motivo: {$equipos['motivo']}",
                                        'UTF-8'
                                    )
                                )));
                            } else {

                                $asi_status = 4;
                                $motivo = htmlspecialchars(trim(mb_strtoupper(mb_convert_encoding($equipos['motivo'], 'UTF-8'))));
                            }


                            $dependencia = $_SESSION['dep_llave'];


                            $asignacion = new AsignacionDependencia([
                                'asi_equipo' => $equipos['idEquipo'],
                                'asi_dependencia' => $dependencia,
                                'asi_destacamento' => $destino,
                                'asi_fecha' => $fecha_actual,
                                'asi_responsable' => $equipos['plaza'],
                                'asi_motivo' => $motivo,
                                'asi_status' => $asi_status,
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
                        'mensaje' => 'Éxito al asignar el(los) equipo(s)'
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

    public static function AgregarAccesoriosNuevosAPI()
    {

        $idEquipo = filter_var($_POST['idEquipo'], FILTER_SANITIZE_NUMBER_INT);
        $idAccesorio = filter_var($_POST['idAccesorio'], FILTER_SANITIZE_NUMBER_INT);
        $cantidad = filter_var($_POST['cantidad'], FILTER_SANITIZE_NUMBER_INT);
        $estado = filter_var($_POST['estado'], FILTER_SANITIZE_NUMBER_INT);


        try {
            $agregar_accesorio = new AsignacionAccesorio([
                'asig_equipo' => $idEquipo,
                'asig_accesorio' => $idAccesorio,
                'asig_cantidad' => $cantidad,
                'asig_estado' => $estado,
                'asig_situacion' => 1
            ]);

            header('Content-Type: application/json');
            $crear = $agregar_accesorio->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Accesorio añadido correctamente'
            ]);
        } catch (Exception $e) {

            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al realizar el registro',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function  EliminarAccesoriosAPI(){

        $idEquipo = filter_var($_POST['idEquipo'], FILTER_SANITIZE_NUMBER_INT);
        $idAccesorio = filter_var($_POST['idAccesorio'], FILTER_SANITIZE_NUMBER_INT);

        try {

            $eliminarAccesorio = AsignacionAccesorio::EliminarAccesorioAsignado($idEquipo,$idAccesorio);
            http_response_code(200);
            echo json_encode([
                'codigo' => 4,
                'mensaje' => 'Accesorio eliminado exitosamente',
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el Accesorio',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}
