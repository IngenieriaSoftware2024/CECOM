<?php

namespace Model;

class Equipo extends ActiveRecord{

    public static $tabla = 'equipo_comunicaciones';
    public static $columnasDB = ['com_serie', 'com_clase', 'com_tipo', 'com_marca', 'com_estado', 'com_situacion'];
    public static $idTabla = 'com_id';

    public $com_id;
    public $com_serie;
    public $com_clase;
    public $com_tipo;
    public $com_marca;
    public $com_estado;
    public $com_situacion;

    public function __construct($args = []){
        
        $this->com_id = $args['com_id'] ?? null;
        $this->com_serie = utf8_decode( mb_strtoupper($args['com_serie'])) ?? '';
        $this->com_clase = $args['com_clase'] ?? null;
        $this->com_tipo = $args['com_tipo'] ?? null;
        $this->com_marca = $args['com_marca'] ?? null;
        $this->com_estado = $args['com_estado'] ?? null;
        $this->com_situacion = $ars['com_situacion'] ?? null;
    }
}