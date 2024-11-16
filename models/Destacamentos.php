<?php

namespace Model;

class Destacamentos extends ActiveRecord
{
    public static $tabla = 'cecom_dest_brgs';
    public static $columnasDB = ['ubi_dependencia', 'ubi_nombre', 'ubi_latitud', 'ubi_longitud', 'ubi_situacion'];
    public static $idTabla = 'ubi_id';

    public $ubi_id;
    public $ubi_dependencia;
    public $ubi_nombre;
    public $ubi_latitud;
    public $ubi_longitud;
    public $ubi_situacion;
    public function __construct($args = [])
    {
     

        $this->ubi_id = $args['ubi_id'] ?? null;
        $this->ubi_dependencia = $args['ubi_dependencia'] ?? '';
        $this->ubi_nombre = utf8_decode( mb_strtoupper($args['ubi_nombre'])) ?? '';
        $this->ubi_latitud = $args['ubi_latitud'] ?? '';
        $this->ubi_longitud = $args['ubi_longitud'] ?? '';
        $this->ubi_situacion = $args['ubi_situacion'] ?? 1;


    }

    public static function Dependencia($catalogo)
    {
        
        $sql = "SELECT dep_desc_lg as dependencia from mper inner join morg on per_plaza = org_plaza inner join mdep on org_dependencia = dep_llave where per_catalogo = '$catalogo'";
        
        return self::fetchFirst($sql);
    }
    public static function BuscarDestacamentos($dependencia)
    {
        $sql = "SELECT * FROM CECOM_DEST_BRGS WHERE UBI_DEPENDENCIA  = '$dependencia' AND UBI_SITUACION = 1";

        return self::fetchArray($sql);
    }

    public static function DestacamentosDependencia($dep_llave)
    {
        $sql = "SELECT UBI_ID, UBI_NOMBRE FROM CECOM_DEST_BRGS WHERE UBI_DEPENDENCIA  = '$dep_llave'";

        return self::fetchArray($sql);
    }
}