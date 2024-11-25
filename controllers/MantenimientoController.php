<?php

namespace Controllers;

use Exception;
use Model\AsignacionDependencia;
use Model\Mantenimiento;
use MVC\Router;

class MantenimientoController
{
    public static function index(Router $router)
    {
        isAuth();
        hasPermission(['CECOM_ADMINISTR']);

        $router->render('mantenimientos/mantenimiento', []);
    }

    public static function buscarAPI()
    {
        try {

            $data = Mantenimiento::BuscarPendienteRecibo();
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

    public static function  buscarEntregaAPI()
    {
        try {

            $data = Mantenimiento::BuscarEntrega();
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

    public static function ValidarCatalogoAPI()
    {

        $catalogo = filter_var($_GET['catalogo'], FILTER_SANITIZE_NUMBER_INT);

        try {

            $data = Mantenimiento::ValidarCatalogo($catalogo);
            http_response_code(200);
            echo json_encode([
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

    public static function GuardarAPI()
    {

        if (isset($_POST)) {

            $asi_id = filter_var($_POST['asi_id'], FILTER_SANITIZE_NUMBER_INT);
            $rep_equipo = filter_var($_POST['rep_equipo'], FILTER_SANITIZE_NUMBER_INT);
            $rep_entrego = filter_var($_POST['rep_entrego'], FILTER_SANITIZE_NUMBER_INT);
            $rep_desc = htmlspecialchars(trim(mb_strtoupper(mb_convert_encoding($_POST['rep_desc'], 'UTF-8'))));
            $rep_estado_ant = filter_var($_POST['rep_estado_ant'], FILTER_SANITIZE_NUMBER_INT);
            $rep_responsable = filter_var($_POST['rep_responsable'], FILTER_SANITIZE_NUMBER_INT);

            if (!$asi_id || !$rep_equipo || !$rep_entrego || !$rep_desc || !$rep_estado_ant || !$rep_responsable) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Faltan datos obligatorios en la solicitud'
                ]);
                http_response_code(400);
                exit;
            }
        }

        try {

            $conexion = Mantenimiento::getDB();
            $conexion->beginTransaction();

            $equipo = Mantenimiento::CambioSituacionEquipo($asi_id, $rep_equipo);

            if ($equipo) {

                $fecha_entrada = date("Y-m-d H:i");

                $detalle_equipo = new Mantenimiento([
                    'rep_equipo' => $rep_equipo,
                    'rep_fecha_entrada' => $fecha_entrada,
                    'rep_entrego' => $rep_entrego,
                    'rep_desc' => $rep_desc,
                    'rep_estado_ant' => $rep_estado_ant,
                    'rep_responsable' => $rep_responsable,
                    'rep_status' => 9
                ]);

                $registro = $detalle_equipo->crear();
            }

            $conexion->commit();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito Equipo Registrado Correctamente'
            ]);
        } catch (Exception $e) {
            $conexion->rollBack();

            http_response_code(500);
            echo json_encode([
                'mensaje' => 'Error al registrar este equipo y accesorios',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function datosEquipoAPI()
    {

        $id = filter_var($_GET['equipo'], FILTER_SANITIZE_NUMBER_INT);

        try {

            $data = Mantenimiento::DatosEquipo($id);
            http_response_code(200);
            echo json_encode([
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

    public static function EntregarAPI()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Método de solicitud no permitido'
            ]);
            http_response_code(405);
            exit;
        }

        $rep_id = filter_input(INPUT_POST, 'rep_id', FILTER_VALIDATE_INT);
        $asi_id = filter_input(INPUT_POST, 'asi_id', FILTER_VALIDATE_INT);
        $rep_equipo = filter_input(INPUT_POST, 'rep_equipo', FILTER_VALIDATE_INT);
        $rep_fecha_entrada = date('Y-m-d H:i', strtotime($_POST['rep_fecha_entrada']));
        $rep_entrego = filter_input(INPUT_POST, 'rep_entrego', FILTER_VALIDATE_INT);
        $rep_recibido = filter_input(INPUT_POST, 'rep_recibido', FILTER_VALIDATE_INT);
        $rep_desc = htmlspecialchars(trim(mb_strtoupper(mb_convert_encoding($_POST['rep_desc'] ?? '', 'UTF-8'))));
        $rep_estado_ant = filter_input(INPUT_POST, 'rep_estado_ant', FILTER_VALIDATE_INT);
        $rep_estado_actual = filter_input(INPUT_POST, 'rep_estado_actual', FILTER_VALIDATE_INT);
        $rep_responsable = filter_input(INPUT_POST, 'rep_responsable', FILTER_VALIDATE_INT);
        $rep_obs = htmlspecialchars(trim(mb_strtoupper(mb_convert_encoding($_POST['rep_obs'] ?? '', 'UTF-8'))));


        if (
            !$rep_id || !$asi_id || !$rep_equipo || !$rep_entrego ||
            !$rep_recibido || !$rep_desc || !$rep_estado_ant ||
            !$rep_estado_actual || !$rep_responsable
        ) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Faltan datos obligatorios en la solicitud'
            ]);
            http_response_code(400);
            exit;
        }

        try {

            $conexion = Mantenimiento::getDB();
            $conexion->beginTransaction();

            $cambiar_situacion = AsignacionDependencia::cambioSituacionEquipo($asi_id, $rep_equipo);

            if (!$cambiar_situacion) {
                throw new Exception('No se pudo cambiar la situación del equipo.');
            }

            $datos_anteriores = Mantenimiento::DatosDevolucionEquipo($asi_id, $rep_equipo);
            $fecha_actual = date("Y-m-d");

            if (!$datos_anteriores) {
                throw new Exception('No se encontraron datos anteriores para la devolución.');
            }

            $asignacion = new AsignacionDependencia([
                'asi_equipo' => $datos_anteriores['asi_equipo'],
                'asi_dependencia' => $datos_anteriores['asi_dependencia'],
                'asi_destacamento' => '',
                'asi_fecha' => $fecha_actual,
                'asi_responsable' => $datos_anteriores['asi_responsable'],
                'asi_motivo' => "Devolucion de Equipo a su dependencia por motivo: Reparacion y/o Mantenimiento",
                'asi_status' => 4,
                'asi_situacion' => 1
            ]);

            $asignar = $asignacion->crear();

            if ($asignar) {

                $fecha_salida = date("Y-m-d H:i");

                $data = Mantenimiento::find($rep_id);
                $data->sincronizar([
                    'rep_equipo' => $rep_equipo,
                    'rep_fecha_entrada' => $rep_fecha_entrada,
                    'rep_fecha_salida' => $fecha_salida,
                    'rep_entrego' => $rep_entrego,
                    'rep_recibido' => $rep_recibido,
                    'rep_desc' => $rep_desc,
                    'rep_estado_ant' => $rep_estado_ant,
                    'rep_estado_actual' => $rep_estado_actual,
                    'rep_responsable' => $rep_responsable,
                    'rep_status' => 10,
                    'rep_obs' => $rep_obs
                ]);
                $data->actualizar();
            }

            $conexion->commit();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito, Equipo Entregado exitosamente'
            ]);
        } catch (Exception $e) {

            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }

            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ocurrió un error al procesar la solicitud.',
                'detalle' => $e->getMessage()
            ]);

            error_log($e->getMessage());

            http_response_code(500);
        }
    }
}
