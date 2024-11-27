<?php

namespace Controllers;

use Exception;
use Model\AsignacionDependencia;
use Model\Destacamentos;
use Model\Equipo;
use MVC\Router;

class ReportesController
{
    public static function index(Router $router)
    {
        isAuth();
        hasPermission(['CECOM_ADMINISTR', 'CECOM_USUARIO']);

        $destamentos = Destacamentos::TodosDestacamentosActivos();
        $dependencias = AsignacionDependencia::Dependencias();
        $marcas = Equipo::ObtenerMarcas();

        $router->render('reportes/index', [
            'marcas' => $marcas,
            'dependencias' => $dependencias,
            'destacamentos' => $destamentos
        ]);
    }

    public static function indexHistorial(Router $router)
    {
        isAuth();
        hasPermission(['CECOM_ADMINISTR']);
        $router->render('reportes/historial_mantto', []);
    }



    public static function BuscarCondiciones()
    {

        $eqp_clase = filter_var($_POST['eqp_clase'], FILTER_SANITIZE_NUMBER_INT);
        $eqp_serie = $_POST['eqp_serie'];
        $eqp_gama = filter_var($_POST['eqp_gama'], FILTER_SANITIZE_NUMBER_INT);
        $eqp_marca = filter_var($_POST['eqp_marca'], FILTER_SANITIZE_NUMBER_INT);
        $eqp_estado = filter_var($_POST['eqp_estado'], FILTER_SANITIZE_NUMBER_INT);
        $asi_status = filter_var($_POST['asi_status'], FILTER_SANITIZE_NUMBER_INT);
        $rep_status = filter_var($_POST['rep_status'], FILTER_SANITIZE_NUMBER_INT);
        $asi_dependencia = filter_var($_POST['asi_dependencia'], FILTER_SANITIZE_NUMBER_INT);
        $ubi_id = filter_var($_POST['ubi_id'], FILTER_SANITIZE_NUMBER_INT);


        try {

            if ($asi_status == 7) {
                $Situacion = 0;
            } else {
                $Situacion = 1;
            }

            $sql = "SELECT EQP_ID, EQP_CLASE, EQP_SERIE, EQP_GAMA, EQP_MARCA, EQP_ESTADO, DEP_DESC_MD AS DEPENDENCIA, 
                clase.CAR_NOMBRE AS CLASE, gama.CAR_NOMBRE AS GAMA, MAR_DESCRIPCION AS MARCA, ASI_STATUS, 
                ubi.UBI_NOMBRE AS DESTACAMENTO, rep.REP_STATUS AS ESTADO_REPARACION
                FROM CECOM_EQUIPO
                INNER JOIN CECOM_ASIG_EQUIPO ON ASI_EQUIPO = EQP_ID
                LEFT JOIN MDEP ON DEP_LLAVE = ASI_DEPENDENCIA
                INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP_CLASE
                LEFT JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP_GAMA
                INNER JOIN CECOM_MARCAS ON EQP_MARCA = MAR_ID
                LEFT JOIN CECOM_DEST_BRGS AS ubi ON ubi.UBI_ID = ASI_DESTACAMENTO
                LEFT JOIN CECOM_MANTTO_REP AS rep ON rep.REP_EQUIPO = EQP_ID
                WHERE ASI_SITUACION = $Situacion";

            if ($eqp_clase != '') {
                $sql .= " AND eqp_clase = $eqp_clase";
            }
            if ($eqp_serie != '') {
                $sql .= " AND eqp_serie LIKE '%" . $eqp_serie . "%'";
            }
            if ($eqp_gama != '') {
                $sql .= " AND eqp_gama = $eqp_gama";
            }
            if ($eqp_marca != '') {
                $sql .= " AND eqp_marca = $eqp_marca";
            }
            if ($eqp_estado != '') {
                $sql .= " AND eqp_estado = $eqp_estado";
            }
            if ($asi_status != '') {
                $sql .= " AND asi_status = $asi_status";
            }
            if ($rep_status != '') {
                $sql .= " AND rep.REP_STATUS = $rep_status";
            }
            if ($asi_dependencia != '') {
                $sql .= " AND asi_dependencia = $asi_dependencia";
            }
            if ($ubi_id != '') {
                $sql .= " AND ubi_id = $ubi_id";
            }

            if ($_SESSION['CECOM_USUARIO']) {

                $dependencia = $_SESSION['dep_llave'];
                $sql .= " AND asi_dependencia = $dependencia";
            }


            $data = Equipo::fetchArray($sql);


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

    public static function HistorialBuscarAPI()
    {
        try {

            $sql = "SELECT 
                    R.REP_ID,
                    -- Otros campos de la tabla CECOM_MANTTO_REP
                    MAX(R.REP_FECHA_ENTRADA) AS REP_FECHA_ENTRADA,
                    MAX(R.REP_FECHA_SALIDA) AS REP_FECHA_SALIDA,
                    MAX(R.REP_DESC) AS REP_DESC,
                    
                    -- Descripciones de los estados
                    MAX(SIT_ANT.SIT_DESCRIPCION) AS REP_ESTADO_ANT_DESC,
                    MAX(SIT_ACT.SIT_DESCRIPCION) AS REP_ESTADO_ACTUAL_DESC,
                    MAX(SIT_STATUS.SIT_DESCRIPCION) AS REP_STATUS_DESC,
                    
                    MAX(R.REP_OBS) AS REP_OBS,
                    
                    EQP.EQP_ID,
                    -- Concatenación de nombre completo para el responsable
                    TRIM(GRA.GRA_DESC_LG) || ' DE ' || TRIM(ARM.ARM_DESC_LG) || ' ' || TRIM(PER.PER_NOM1) || ' ' || TRIM(PER.PER_NOM2) || ' ' || TRIM(PER.PER_APE1) || ' ' || TRIM(PER.PER_APE2) AS RESPONSABLE_NOMBRE_COMPLETO,
                    -- Concatenación de nombre completo para quien entregó
                    TRIM(GRA1.GRA_DESC_LG) || ' DE ' || TRIM(ARM1.ARM_DESC_LG) || ' ' || TRIM(PER1.PER_NOM1) || ' ' || TRIM(PER1.PER_NOM2) || ' ' || TRIM(PER1.PER_APE1) || ' ' || TRIM(PER1.PER_APE2) AS ENTREGADO_NOMBRE_COMPLETO,
                    -- Concatenación de nombre completo para quien recibió
                    TRIM(GRA2.GRA_DESC_LG) || ' DE ' || TRIM(ARM2.ARM_DESC_LG) || ' ' || TRIM(PER2.PER_NOM1) || ' ' || TRIM(PER2.PER_NOM2) || ' ' || TRIM(PER2.PER_APE1) || ' ' || TRIM(PER2.PER_APE2) AS RECIBIDO_NOMBRE_COMPLETO,
                    -- Concatenando clase, marca, gama y serie en un solo campo
                    TRIM(clase.CAR_NOMBRE) || '  ' || TRIM(MARC.MAR_DESCRIPCION) || '  ' || TRIM(gama.CAR_NOMBRE) || ' serie:  ' || TRIM(EQP.EQP_SERIE) AS EQUIPO_DESCRIPCION,
                    
                    TRIM(DEP.DEP_DESC_MD) AS DEPENDENCIA
                FROM 
                    CECOM_MANTTO_REP R
                    -- Información del equipo
                    INNER JOIN CECOM_EQUIPO EQP ON EQP.EQP_ID = R.REP_EQUIPO
                    INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP.EQP_CLASE
                    INNER JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP.EQP_GAMA
                    INNER JOIN CECOM_MARCAS MARC ON EQP.EQP_MARCA = MARC.MAR_ID
                    INNER JOIN CECOM_SITUACIONES ON EQP.EQP_ESTADO = CECOM_SITUACIONES.SIT_LLAVE
                    -- Información sobre la asignación del equipo para obtener la dependencia
                    INNER JOIN CECOM_ASIG_EQUIPO ON CECOM_ASIG_EQUIPO.ASI_EQUIPO = EQP.EQP_ID
                    INNER JOIN MDEP DEP ON DEP.DEP_LLAVE = CECOM_ASIG_EQUIPO.ASI_DEPENDENCIA
                    -- Información sobre el responsable
                    LEFT JOIN MPER PER ON PER.PER_CATALOGO = R.REP_RESPONSABLE
                    LEFT JOIN ARMAS ARM ON ARM.ARM_CODIGO = PER.PER_ARMA
                    LEFT JOIN GRADOS GRA ON GRA.GRA_CODIGO = PER.PER_GRADO
                    -- Información sobre quien entregó
                    LEFT JOIN MPER PER1 ON PER1.PER_CATALOGO = R.REP_ENTREGO
                    LEFT JOIN ARMAS ARM1 ON ARM1.ARM_CODIGO = PER1.PER_ARMA
                    LEFT JOIN GRADOS GRA1 ON GRA1.GRA_CODIGO = PER1.PER_GRADO
                    -- Información sobre quien recibió
                    LEFT JOIN MPER PER2 ON PER2.PER_CATALOGO = R.REP_RECIBIDO
                    LEFT JOIN ARMAS ARM2 ON ARM2.ARM_CODIGO = PER2.PER_ARMA
                    LEFT JOIN GRADOS GRA2 ON GRA2.GRA_CODIGO = PER2.PER_GRADO
                    
                    -- Información de los estados (JOIN con CECOM_SITUACIONES)
                    LEFT JOIN CECOM_SITUACIONES SIT_ANT ON SIT_ANT.SIT_LLAVE = R.REP_ESTADO_ANT
                    LEFT JOIN CECOM_SITUACIONES SIT_ACT ON SIT_ACT.SIT_LLAVE = R.REP_ESTADO_ACTUAL
                    LEFT JOIN CECOM_SITUACIONES SIT_STATUS ON SIT_STATUS.SIT_LLAVE = R.REP_STATUS

                GROUP BY 
                    R.REP_ID, EQP.EQP_ID, clase.CAR_NOMBRE, MARC.MAR_DESCRIPCION, gama.CAR_NOMBRE, EQP.EQP_SERIE, DEP.DEP_DESC_MD,
                    -- Se agregan las columnas concatenadas en el GROUP BY
                    GRA.GRA_DESC_LG, ARM.ARM_DESC_LG, PER.PER_NOM1, PER.PER_NOM2, PER.PER_APE1, PER.PER_APE2,
                    GRA1.GRA_DESC_LG, ARM1.ARM_DESC_LG, PER1.PER_NOM1, PER1.PER_NOM2, PER1.PER_APE1, PER1.PER_APE2,
                    GRA2.GRA_DESC_LG, ARM2.ARM_DESC_LG, PER2.PER_NOM1, PER2.PER_NOM2, PER2.PER_APE1, PER2.PER_APE2,
                    SIT_ANT.SIT_DESCRIPCION, SIT_ACT.SIT_DESCRIPCION, SIT_STATUS.SIT_DESCRIPCION

                ORDER BY R.REP_ID ASC";

            $data = Equipo::fetchArray($sql);

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

    public static function InformacionEquipoSeleccionadoAPI()
    {
 
        $idEquipo = filter_var($_POST['idEquipo'], FILTER_SANITIZE_NUMBER_INT);
        $Status = filter_var($_POST['Status'], FILTER_SANITIZE_NUMBER_INT);
     
        try {
            $Informacion = Destacamentos::InformacionGeneral($idEquipo, $Status);
            $Accesorios = Destacamentos::AccesorioEquipo($idEquipo);
            $Movimientos = Destacamentos::HistorialMovimientos($idEquipo);
            $Mantenimientos = Destacamentos::HistorialMantenimientos($idEquipo); 
            
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Exito, busqueda completada',
                'Informacion' => $Informacion,
                'Accesorios' => $Accesorios,
                'Movimientos' => $Movimientos,
                'Mantenimientos' => $Mantenimientos
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

    public static function DestacamentosDependenciaAPI(){

        $dependencia = filter_var($_GET['dependencia'], FILTER_SANITIZE_NUMBER_INT);

        $sql = "SELECT UBI_ID, UBI_NOMBRE FROM CECOM_DEST_BRGS WHERE UBI_SITUACION = 1 AND ubi_dependencia = $dependencia";

        $data = Equipo::fetchArray($sql);

        echo json_encode($data);
        
    }
}
