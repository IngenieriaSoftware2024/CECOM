<?php

namespace Model;

class Equipo extends ActiveRecord
{
    public static $tabla = 'cecom_equipo';
    public static $columnasDB = ['eqp_clase', 'eqp_serie', 'eqp_gama', 'eqp_marca', 'eqp_estado'];
    public static $idTabla = 'eqp_id';

    public $eqp_id;
    public $eqp_clase;
    public $eqp_serie;
    public $eqp_gama;
    public $eqp_marca;
    public $eqp_estado;


    public function __construct($args = [])
    {


        $this->eqp_id = $args['eqp_id'] ?? null;
        $this->eqp_clase = $args['eqp_clase'] ?? null;
        $this->eqp_serie = utf8_decode(mb_strtoupper($args['eqp_serie'])) ?? '';
        $this->eqp_gama = $args['eqp_gama'] ?? null;
        $this->eqp_marca = $args['eqp_marca'] ?? null;
        $this->eqp_estado = $args['eqp_estado'] ?? null;
    }

    public static function ObtenerMarcas()
    {
        $sql = "SELECT * FROM cecom_marcas";

        return self::fetchArray($sql);
    }

    public static function AccesoriosEquipo($tipo)
    {
        $sql = "SELECT ACC_ID, ACC_NOMBRE, ACC_TIPO, CAR_NOMBRE FROM CECOM_ACCESORIOS
                INNER JOIN CECOM_CARACTERISTICAS ON CAR_ID = ACC_TIPO WHERE ACC_TIPO = $tipo";

        return self::fetchArray($sql);
    }

    public static function VerificarSerie($serie)
    {
        $sql = "SELECT COUNT(EQP_SERIE) AS CANTIDAD FROM CECOM_EQUIPO WHERE EQP_SERIE = '$serie'";
        return self::fetchArray($sql);
    }

    public static function UltimoID()
    {
        $sql = "SELECT MAX(EQP_ID) AS ultimo_id FROM CECOM_EQUIPO";
        return self::fetchArray($sql);
    }

    public static function BuscarPlazaOfsGuardalmacen($dep_llave)
    {
        $sql = "SELECT  org_plaza from morg where org_ceom = 'O44E30'  AND org_dependencia = '$dep_llave' ";

        return self::fetchFirst($sql);
    }

    public static function ObtenerTodosEquipos()
    {
        $sql = "SELECT EQP_ID, EQP_CLASE, EQP_SERIE, EQP_GAMA, EQP_MARCA, EQP_ESTADO, DEP_DESC_MD AS DEPENDENCIA,
                clase.CAR_NOMBRE AS CLASE, gama.CAR_NOMBRE AS GAMA, MAR_DESCRIPCION AS MARCA FROM CECOM_EQUIPO
                INNER JOIN CECOM_ASIG_EQUIPO ON ASI_EQUIPO = EQP_ID
                INNER JOIN MDEP ON DEP_LLAVE = ASI_DEPENDENCIA
                INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP_CLASE
                INNER JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP_GAMA
                INNER JOIN CECOM_MARCAS ON EQP_MARCA = MAR_ID
                WHERE ASI_SITUACION = 1";

        return self::fetchArray($sql);
    }

    public static function ObtenerAccesoriosAsignadosEquipo($equipo)
    {
        $sql = "SELECT * FROM CECOM_ASIG_ACCESORIOS WHERE ASIG_EQUIPO = '$equipo' AND ASIG_SITUACION = 1";

        return self::fetchArray($sql);
    }
};
