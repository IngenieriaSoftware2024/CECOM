<?php

namespace Controllers;

use Model\AsignacionDependencia;
use Model\Destacamentos;
use Model\Equipo;
use MVC\Router;

class ReportesController
{
    public static function index(Router $router)
    {
        $destamentos = Destacamentos::TodosDestacamentosActivos();
        $dependencias = AsignacionDependencia::Dependencias();
        $marcas = Equipo::ObtenerMarcas();

        $router->render('reportes/index', [
            'marcas' => $marcas,
            'dependencias' => $dependencias,
            'destacamentos' => $destamentos
        ]);
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

        
        $sql = "SELECT EQP_ID, EQP_CLASE, EQP_SERIE, EQP_GAMA, EQP_MARCA, EQP_ESTADO, DEP_DESC_MD AS DEPENDENCIA, 
            clase.CAR_NOMBRE AS CLASE, gama.CAR_NOMBRE AS GAMA, MAR_DESCRIPCION AS MARCA, ASI_STATUS, 
            ubi.UBI_NOMBRE AS DESTACAMENTO, rep.REP_STATUS AS ESTADO_REPARACION
            FROM CECOM_EQUIPO
            INNER JOIN CECOM_ASIG_EQUIPO ON ASI_EQUIPO = EQP_ID
            INNER JOIN MDEP ON DEP_LLAVE = ASI_DEPENDENCIA
            INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP_CLASE
            LEFT JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP_GAMA
            INNER JOIN CECOM_MARCAS ON EQP_MARCA = MAR_ID
            LEFT JOIN CECOM_DEST_BRGS AS ubi ON ubi.UBI_ID = ASI_DESTACAMENTO
            LEFT JOIN CECOM_MANTTO_REP AS rep ON rep.REP_EQUIPO = EQP_ID
            WHERE ASI_SITUACION = 1"; 

        
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

       $data = Equipo::fetchArray($sql);

       http_response_code(200);
       echo json_encode([
           'codigo' => 1,
           'mensaje' => "Datos encontrados correctamente",
           'data' => $data
       ]);

    }
}
