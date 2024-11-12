<?php

namespace Model;

class Marca extends ActiveRecord
{
    public static $tabla = 'cecom_marcas';
    public static $columnasDB = ['mar_descripcion'];
    public static $idTabla = 'mar_id';

    public $mar_id;
    public $mar_descripcion;
    
    public function __construct($args = [])
    {
     

        $this->mar_id = $args['mar_id'] ?? null;

        $this->mar_descripcion = utf8_decode( mb_strtoupper($args['mar_descripcion'])) ?? '';
    }
}