<?php

namespace Model;

class Marca extends ActiveRecord
{
    public static $tabla = 'accesorios_comunicaciones';
    public static $columnasDB = ['acc_nombre', 'acc_desc', 'acc_tipo'];
    public static $idTabla = 'mar_id';

    public $mar_id;
    public $acc_nombre;
    public $acc_desc;
    public $acc_tipo;
    public function __construct($args = [])
    {
     

        $this->mar_id = $args['mar_id'] ?? null;

        $this->acc_nombre = utf8_decode( mb_strtoupper($args['acc_nombre'])) ?? '';
        $this->acc_nombre = utf8_decode( mb_strtoupper($args['acc_desc'])) ?? '';
        $this->acc_tipo = $args['acc_tipo'] ?? '';
    }
}