<?php

namespace Model;

class AsignacionDependencia extends ActiveRecord
{
    public static $tabla = 'cecom_asig_equipo';
    public static $columnasDB = ['asi_equipo', 'asi_dependencia','asi_destacamento', 'asi_fecha', 'asi_responsable', 'asi_motivo', 'asi_status', 'asi_situacion'];
    public static $idTabla = 'asi_id';

    public $asi_id;
    public $asi_equipo;
    public $asi_dependencia;
    public $asi_destacamento;
    public $asi_fecha;
    public $asi_responsable;
    public $asi_motivo;
    public $asi_status;
    public $asi_situacion;
    public function __construct($args = [])
    {

        $this->asi_id = $args['asi_id'] ?? null;
        $this->asi_equipo = $args['asi_equipo'] ?? null;
        $this->asi_dependencia = $args['asi_dependencia'] ?? null;
        $this->asi_destacamento = $args['asi_destacamento'] ?? null;
        $this->asi_fecha = $args['asi_fecha'] ?? '';
        $this->asi_responsable = $args['asi_responsable'] ?? '';
        $this->asi_motivo = utf8_decode( mb_strtoupper($args['asi_motivo'])) ?? '';
        $this->asi_status = $args['asi_status'] ?? '';
        $this->asi_situacion = $args['asi_situacion'] ?? 1;
    }


    public static function EquiposAlmacen($dep_llave)
    {
        $sql = "SELECT ASI_ID, EQP_ID, EQP_SERIE AS SERIE, clase.CAR_NOMBRE AS CLASE, gama.CAR_NOMBRE AS GAMA, 
                MAR_DESCRIPCION AS MARCA, SIT_DESCRIPCION AS SITUACION, DEP_DESC_MD AS DEPENDENCIA FROM CECOM_EQUIPO
                INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP_CLASE
                INNER JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP_GAMA
                INNER JOIN CECOM_MARCAS ON EQP_MARCA = MAR_ID
                INNER JOIN CECOM_SITUACIONES ON EQP_SITUACION = SIT_LLAVE
                INNER JOIN CECOM_ASIG_EQUIPO ON ASI_EQUIPO = EQP_ID
                INNER JOIN MDEP ON DEP_LLAVE = ASI_DEPENDENCIA
                WHERE ASI_DEPENDENCIA = '$dep_llave' AND ASI_SITUACION= 1 AND ASI_STATUS = 5";
        
        return self::fetchArray($sql);
    }

    public static function Dependencias()
    {
        $sql = "SELECT dep_llave,  dep_desc_md
                FROM mdep
                LEFT JOIN morg ON org_dependencia = dep_llave
                LEFT JOIN mper ON per_plaza = org_plaza
                WHERE (per_situacion IN ('11', 'TH', 'T0') OR dep_situacion = 1)
                AND dep_situacion = 1
                GROUP BY dep_llave, dep_desc_lg, dep_desc_md, dep_desc_ct, dep_clase, dep_precio, dep_ejto, dep_situacion
                ORDER BY dep_situacion, dep_desc_md";

        return self::fetchArray($sql);
    }

    public static function InformacionOficial($catalogo, $dependencia)
    {
        $sql  =  "SELECT TRIM(GRA_DESC_LG)||' DE '|| TRIM(ARM_DESC_LG) AS GRADO_ARMA, TRIM(PER_APE1)||' '||TRIM(PER_APE2)||' '||TRIM(PER_NOM1)||' '||TRIM(PER_NOM2) 
                    AS NOMBRE_COMPLETO, PER_PLAZA, PER_CATALOGO
                    FROM MPER 
                    INNER JOIN ARMAS ON ARM_CODIGO = PER_ARMA
                    INNER JOIN GRADOS ON GRA_CODIGO = PER_GRADO
                    INNER JOIN MORG ON ORG_PLAZA = PER_PLAZA
                    WHERE PER_CATALOGO = '$catalogo' AND ORG_DEPENDENCIA = '$dependencia'";

        return self::fetchArray($sql);
    }

    public static function cambioSituacionEquipo($asi_id, $asi_equipo)
    {
        $sql = "UPDATE CECOM_ASIG_EQUIPO SET ASI_SITUACION = 0 WHERE ASI_ID = '$asi_id' AND  ASI_EQUIPO = '$asi_equipo' ";

        return self::SQL($sql);
    }

    public static function BuscarTodos()
    {
        $sql = "SELECT ASI_ID, EQP_ID, EQP_SERIE AS SERIE, clase.CAR_NOMBRE AS CLASE, gama.CAR_NOMBRE AS GAMA, 
                MAR_DESCRIPCION AS MARCA, SIT_DESCRIPCION AS SITUACION, DEP_DESC_MD AS DEPENDENCIA FROM CECOM_EQUIPO
                INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP_CLASE
                INNER JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP_GAMA
                INNER JOIN CECOM_MARCAS ON EQP_MARCA = MAR_ID
                INNER JOIN CECOM_SITUACIONES ON EQP_SITUACION = SIT_LLAVE
                INNER JOIN CECOM_ASIG_EQUIPO ON ASI_EQUIPO = EQP_ID
                INNER JOIN MDEP ON DEP_LLAVE = ASI_DEPENDENCIA
                WHERE ASI_SITUACION= 1 ORDER BY DEP_DESC_MD";
        
        return self::fetchArray($sql);
    }

    public static function EquiposDependencias($dep_llave)
    {
        $sql = "SELECT ASI_ID, EQP_ID, EQP_SERIE AS SERIE, clase.CAR_NOMBRE AS CLASE, gama.CAR_NOMBRE AS GAMA, MAR_DESCRIPCION AS MARCA, 
                situacion_status.SIT_DESCRIPCION AS ESTATUS, TRIM(gra_desc_lg)||' DE '||TRIM(arm_desc_lg)||' '||
                TRIM(per_nom1)|| ' ' ||TRIM(per_nom2)|| ' ' ||TRIM(per_ape1)|| ' ' ||TRIM(per_ape2)AS RESPONSABLE FROM CECOM_EQUIPO
                INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP_CLASE
                INNER JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP_GAMA
                INNER JOIN CECOM_MARCAS ON EQP_MARCA = MAR_ID
                INNER JOIN CECOM_ASIG_EQUIPO ON ASI_EQUIPO = EQP_ID
                INNER JOIN CECOM_SITUACIONES AS situacion_status ON CECOM_ASIG_EQUIPO.ASI_STATUS = situacion_status.SIT_LLAVE
                INNER JOIN MPER ON PER_PLAZA = ASI_RESPONSABLE
                INNER JOIN grados ON gra_codigo = per_grado
                INNER JOIN armas ON arm_codigo = per_arma
                WHERE ASI_DEPENDENCIA = '$dep_llave' AND ASI_SITUACION = 1";

        return self::fetchArray($sql);
    }

    public static function ResponsableEnviarMantenimiento($catalogo){

        $sql = "SELECT TRIM(GRA_DESC_LG)||' DE '|| TRIM(ARM_DESC_LG) AS GRADO_ARMA, TRIM(PER_APE1)||' '||
                TRIM(PER_APE2)||' '||TRIM(PER_NOM1)||' '||TRIM(PER_NOM2)  AS NOMBRE_COMPLETO FROM MPER  
                INNER JOIN ARMAS ON ARM_CODIGO = PER_ARMA 
                INNER JOIN GRADOS ON GRA_CODIGO = PER_GRADO 
                WHERE PER_CATALOGO = '$catalogo'";

        return self::fetchFirst($sql);
    }
}