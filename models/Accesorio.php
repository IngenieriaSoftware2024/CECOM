<?php

namespace Model;

class Accesorio extends ActiveRecord
{
    public static $tabla = 'cecom_accesorios';
    public static $columnasDB = ['acc_nombre', 'acc_tipo'];
    public static $idTabla = 'acc_id';

    public $acc_id;
    public $acc_nombre;
    public $acc_tipo;
    public function __construct($args = [])
    {


        $this->acc_id = $args['acc_id'] ?? null;

        $this->acc_nombre = utf8_decode(mb_strtoupper($args['acc_nombre'])) ?? '';
        $this->acc_tipo = $args['acc_tipo'] ?? '';
    }

    public static function Buscar()
    {
        $sql = "SELECT ACC_ID, ACC_NOMBRE, ACC_TIPO, CAR_NOMBRE FROM CECOM_ACCESORIOS
                INNER JOIN CECOM_CARACTERISTICAS ON CAR_ID = ACC_TIPO ORDER BY ACC_TIPO, ACC_NOMBRE";

        return self::fetchArray($sql);
    }
}
