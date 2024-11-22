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
        $this->ubi_nombre = utf8_decode(mb_strtoupper($args['ubi_nombre'])) ?? '';
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

    public static function DestacamentosActivosGeneral()
    {
        $query = "SELECT * FROM CECOM_DEST_BRGS WHERE UBI_SITUACION = 1";

        return self::fetchArray($query);
    }


    public static function EquiposPorDestacamentos($id)
    {
        $query = "SELECT EQP_SERIE AS SERIE, clase.CAR_NOMBRE AS CLASE, gama.CAR_NOMBRE AS GAMA, 
                MAR_DESCRIPCION AS MARCA FROM CECOM_EQUIPO
                INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP_CLASE
                INNER JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP_GAMA
                INNER JOIN CECOM_MARCAS ON EQP_MARCA = MAR_ID
                INNER JOIN CECOM_ASIG_EQUIPO ON ASI_EQUIPO = EQP_ID
                WHERE ASI_DESTACAMENTO = '$id' AND ASI_SITUACION= 1 ";

        return self::fetchArray($query);
    }

    public static function ObtenerUbicacionTipoEquipo($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $listadoIds = implode(',', $ids);


        $sql = "SELECT UBI_LATITUD, UBI_LONGITUD, COUNT(ASI_EQUIPO) AS cantidad_equipos FROM CECOM_ASIG_EQUIPO
                INNER JOIN CECOM_DEST_BRGS ON UBI_ID = ASI_DESTACAMENTO
                INNER JOIN CECOM_EQUIPO ON EQP_ID = ASI_EQUIPO
                WHERE EQP_CLASE IN ($listadoIds) AND ASI_SITUACION = 1
                GROUP BY UBI_LATITUD, UBI_LONGITUD";


        return self::fetchArray($sql);
    }

    public static function TodosDestacamentosActivos()
    {
        $sql = "SELECT UBI_ID, UBI_NOMBRE FROM CECOM_DEST_BRGS WHERE UBI_SITUACION = 1";

        return self::fetchArray($sql);
    }
}
