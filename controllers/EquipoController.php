<?php

namespace Controllers;

use Exception;
use Model\AsignacionAccesorio;
use Model\AsignacionDependencia;
use Model\Equipo;
use MVC\Router;

class EquipoController
{
    public static function index(Router $router)
    {

        $marcas = Equipo::ObtenerMarcas();
        $router->render('equipos/index', [
            'marcas' => $marcas
        ]);
    }

    public static function AccesoriosEquipoAPI()
    {
        $id = filter_var($_GET['tipo'], FILTER_SANITIZE_NUMBER_INT);
        try {
            $accesoriosequipo = Equipo::AccesoriosEquipo($id);
            http_response_code(200);
            echo json_encode([
                'accesorios' => $accesoriosequipo
            ]);
        } catch (Exception $error) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al realizar la busqueda',
                'detalle' => $error->getMessage()
            ]);
        }
    }

    public static function VerificarSerieAPI()
    {
        $serie = $_GET['serie'];

        try {
            $valor = Equipo::VerificarSerie($serie);
            http_response_code(200);
            echo json_encode([
                'cantidad' => $valor
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'mensaje' => 'Error al realizar la busqueda',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function GuardarAPI()
    {

        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en la decodificación de los datos JSON.'
            ]);
            return;
        }

        $accesorios = isset($data['accesorios']) ? $data['accesorios'] : [];

        $eqp_clase = $data['eqp_clase'] ?? null;
        $eqp_estado = $data['eqp_estado'] ?? null;
        $eqp_gama = $data['eqp_gama'] ?? null;
        $eqp_id = $data['eqp_id'] ?? null;
        $eqp_marca = $data['eqp_marca'] ?? null;
        $eqp_serie = $data['eqp_serie'] ?? null;

        try {

            //Establecer Conexion y manejo de transacciones
            $conexion = Equipo::getDB();
            $conexion->beginTransaction();

            $equipo = new Equipo([
                'eqp_clase' => $eqp_clase,
                'eqp_serie' => $eqp_serie,
                'eqp_gama' => $eqp_gama,
                'eqp_marca' => $eqp_marca,
                'eqp_estado' => $eqp_estado,
                'eqp_situacion' => 1,

            ]);


            $crear_equipo = $equipo->crear();

            if ($crear_equipo) {

                $id = Equipo::UltimoID();
                $id_json = json_encode($id); 

                
                $id_array = json_decode($id_json, true);
                
                $ultimo_id = $id_array[0]['ultimo_id'] ?? null; 
                

                if ($accesorios && is_array($accesorios)) {
                    
                    foreach ($accesorios as $accesorio) {
                        
                        $asignacion_accesorios = new AsignacionAccesorio([
                            'asig_equipo' => $ultimo_id,
                            'asig_accesorio' => $accesorio['id'],
                            'asig_cantidad' => $accesorio['cantidad'],
                            'asig_estado' => $accesorio['estado'],
                            'asig_situacion' => 1,
                        ]);
                        $asignar_accesorio = $asignacion_accesorios->crear();
                    }

                    if($asignar_accesorio){
                        
                        $brigada_comunicaciones = $_SESSION['dep_llave'];
                        $fecha_actual = date('Y-m-d');
                        $Plaza = Equipo::BuscarPlazaOfsGuardalmacen($brigada_comunicaciones);
                        $responsable = $Plaza['org_plaza'];

                        $asignar_brigada_comnicaciones = new AsignacionDependencia([
                            'asi_equipo' => $ultimo_id,
                            'asi_dependencia' => $brigada_comunicaciones,
                            'asi_destacamento' => '',
                            'asi_fecha' => $fecha_actual,
                            'asi_responsable' => $responsable,
                            'asi_motivo' => 'Se cargo por primera vez al Sistema',
                            'asi_status' => 5,
                            'asi_situacion' => 1,
                        ]);

                        $asignacion_brigada = $asignar_brigada_comnicaciones->crear();
                    }
                }
   
            }
            //Subir los cambios si se cumplen las condiciones
            $conexion->commit();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Equipo y accesorios guardados con éxito.'
            ]);
    

        } catch (Exception $e) {

            //Devuelve los cambios si no se tuvo exito
            $conexion->rollBack();

            http_response_code(500);
            echo json_encode([
                'mensaje' => 'Error al registrar este equipo y accesorios',
                'detalle' => $e->getMessage()
            ]);

        }
    }
}
