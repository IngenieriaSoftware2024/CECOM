<?php

namespace Model;

class Mantenimiento extends ActiveRecord
{
    public static $tabla = 'cecom_mantto_rep';
    public static $columnasDB = ['rep_equipo', 'rep_fecha_entrada', 'rep_fecha_salida', 'rep_entrego', 'rep_recibido', 'rep_desc', 'rep_estado_ant', 'rep_estado_actual', 'rep_responsable', 'rep_status', 'rep_obs'];
    public static $idTabla = 'rep_id';

    public $rep_id;
    public $rep_equipo;
    public $rep_fecha_entrada;
    public $rep_fecha_salida;
    public $rep_entrego;
    public $rep_recibido;
    public $rep_desc;
    public $rep_estado_ant;
    public $rep_estado_actual;
    public $rep_responsable;
    public $rep_status;
    public $rep_obs;

    public function __construct($args = [])
    {


        $this->rep_id = $args['rep_id'] ?? null;
        $this->rep_equipo = $args['rep_equipo'] ?? null;
        $this->rep_fecha_entrada = $args['rep_fecha_entrada'] ?? '';
        $this->rep_fecha_salida = $args['rep_fecha_salida'] ?? null;
        $this->rep_entrego = $args['rep_entrego'] ?? null;
        $this->rep_recibido = $args['rep_recibido'] ?? null;
        $this->rep_desc = $args['rep_desc'] ?? '';
        $this->rep_estado_ant = $args['rep_estado_ant'] ?? '';
        $this->rep_estado_actual = $args['rep_estado_actual'] ?? '';
        $this->rep_responsable = $args['rep_responsable'] ?? '';
        $this->rep_status = $args['rep_status'] ?? '';
        $this->rep_obs = $args['rep_obs'] ?? '';
    }

    public static function BuscarPendienteRecibo()
    {
        $sql = "SELECT ASI_ID, EQP_ID, EQP_SERIE AS SERIE, clase.CAR_NOMBRE AS CLASE, gama.CAR_NOMBRE AS GAMA, 
                MAR_DESCRIPCION AS MARCA,  DEP_DESC_MD AS DEPENDENCIA FROM CECOM_EQUIPO
                INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP_CLASE
                INNER JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP_GAMA
                INNER JOIN CECOM_MARCAS ON EQP_MARCA = MAR_ID
                INNER JOIN CECOM_SITUACIONES ON EQP_ESTADO = SIT_LLAVE
                INNER JOIN CECOM_ASIG_EQUIPO ON ASI_EQUIPO = EQP_ID
                INNER JOIN MDEP ON DEP_LLAVE = ASI_DEPENDENCIA
                WHERE ASI_SITUACION= 1 AND ASI_STATUS = 8";

        return self::fetchArray($sql);
    }

    public static function BuscarEntrega()
    {
        $sql = "SELECT ASI_ID, EQP_ID, EQP_SERIE AS SERIE, clase.CAR_NOMBRE AS CLASE, gama.CAR_NOMBRE AS GAMA, 
                MAR_DESCRIPCION AS MARCA,  DEP_DESC_MD AS DEPENDENCIA FROM CECOM_EQUIPO
                INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP_CLASE
                INNER JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP_GAMA
                INNER JOIN CECOM_MARCAS ON EQP_MARCA = MAR_ID
                INNER JOIN CECOM_SITUACIONES ON EQP_ESTADO = SIT_LLAVE
                INNER JOIN CECOM_ASIG_EQUIPO ON ASI_EQUIPO = EQP_ID
                INNER JOIN MDEP ON DEP_LLAVE = ASI_DEPENDENCIA
                WHERE ASI_SITUACION= 1 AND ASI_STATUS = 6";

        return self::fetchArray($sql);
    }


    public static function ValidarCatalogo($catalogo)
    {
        $query = "SELECT * FROM MPER WHERE PER_CATALOGO = '$catalogo'";

        return self::fetchFirst($query) ? true : false;
    }

    public static function CambioSituacionEquipo($asi_id, $asi_equipo)
    {
        $sql = "UPDATE CECOM_ASIG_EQUIPO SET ASI_STATUS = 6 WHERE ASI_ID = '$asi_id' AND  ASI_EQUIPO = '$asi_equipo'";

        return self::SQL($sql);
    }

    public static function DatosEquipo($equipo)
    {
        $sql = "SELECT  REP_ID, REP_FECHA_ENTRADA, REP_ENTREGO, REP_DESC, REP_ESTADO_ANT, REP_RESPONSABLE  FROM CECOM_MANTTO_REP
                WHERE REP_EQUIPO = '$equipo'";

        return self::fetchFirst($sql);
    }

    public static function DatosDevolucionEquipo($asi_id, $asi_equipo)
    {
        $sql = "SELECT ASI_EQUIPO, ASI_DEPENDENCIA, ASI_RESPONSABLE FROM CECOM_ASIG_EQUIPO 
                WHERE ASI_ID = '$asi_id' AND ASI_EQUIPO = '$asi_equipo'";

        return self::fetchFirst($sql);
    }
}
