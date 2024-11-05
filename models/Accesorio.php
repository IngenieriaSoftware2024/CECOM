<?php

namespace Model;

class Accesorio extends ActiveRecord
{
    public static $tabla = 'accesorios_comunicaciones';
    public static $columnasDB = ['acc_nombre', 'acc_desc', 'acc_tipo'];
    public static $idTabla = 'acc_id';

    public $acc_id;
    public $acc_nombre;
    public $acc_desc;
    public $acc_tipo;
    public function __construct($args = [])
    {


        $this->acc_id = $args['acc_id'] ?? null;

        $this->acc_nombre = utf8_decode(mb_strtoupper($args['acc_nombre'])) ?? '';
        $this->acc_desc = utf8_decode(mb_strtoupper($args['acc_desc'])) ?? '';
        $this->acc_tipo = $args['acc_tipo'] ?? '';
    }

    public static function Buscar()
    {
        $sql = "SELECT ACC_ID, ACC_NOMBRE, ACC_DESC, ACC_TIPO, SIT_DESCRIPCION FROM accesorios_comunicaciones
                INNER JOIN situaciones_cecom ON SIT_LLAVE = ACC_TIPO";
        return self::fetchArray($sql);
    }
}
