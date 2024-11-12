<?php

namespace Model;

class Equipo extends ActiveRecord
{
    public static $tabla = 'cecom_equipo';
    public static $columnasDB = ['eqp_clase','eqp_serie', 'eqp_gama', 'eqp_marca', 'eqp_estado', 'eqp_situacion'];
    public static $idTabla = 'eqp_id';

    public $eqp_id;
    public $eqp_clase;
    public $eqp_serie;
    public $eqp_gama;
    public $eqp_marca;
    public $eqp_estado;
    public $eqp_situacion;

    public function __construct($args = [])
    {


        $this->eqp_id = $args['eqp_id'] ?? null;
        $this->eqp_clase = $args['eqp_clase'] ?? null;
        $this->eqp_serie = utf8_decode(mb_strtoupper($args['eqp_serie'])) ?? '';
        $this->eqp_gama = $args['eqp_gama'] ?? null;
        $this->eqp_marca = $args['eqp_marca'] ?? null;
        $this->eqp_estado = $args['eqp_estado'] ?? null;
        $this->eqp_situacion = $args['eqp_situacion'] ?? 1;
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
};
