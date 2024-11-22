<?php

namespace Model;

class AsignacionAccesorio extends ActiveRecord
{
    public static $tabla = 'cecom_asig_accesorios';
    public static $columnasDB = ['asig_equipo', 'asig_accesorio', 'asig_cantidad', 'asig_estado','asig_situacion'];
    public static $idTabla = 'asig_equipo';

    public $asig_equipo;
    public $asig_accesorio;
    public $asig_cantidad;
    public $asig_estado;
    public $asig_situacion;
    public function __construct($args = [])
    {


        $this->asig_equipo = $args['asig_equipo'] ?? null;
        $this->asig_accesorio = $args['asig_accesorio'] ?? null;
        $this->asig_cantidad = $args['asig_cantidad'] ?? null;
        $this->asig_estado = $args['asig_estado'] ?? null;
        $this->asig_situacion = $args['asig_situacion'] ?? 1;
    }

    public static function EliminarAccesorioAsignado($idEquipo, $idAccesorio ){
        $sql = "UPDATE CECOM_ASIG_ACCESORIOS SET ASIG_SITUACION = 0 WHERE ASIG_EQUIPO = '$idEquipo' AND ASIG_ACCESORIO = '$idAccesorio'";

        return self::SQL($sql);
    }
}


