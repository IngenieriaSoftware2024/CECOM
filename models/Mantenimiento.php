
SELECT ASI_ID, EQP_ID, EQP_SERIE AS SERIE, clase.CAR_NOMBRE AS CLASE, gama.CAR_NOMBRE AS GAMA, 
                MAR_DESCRIPCION AS MARCA,  DEP_DESC_MD AS DEPENDENCIA FROM CECOM_EQUIPO
                INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP_CLASE
                INNER JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP_GAMA
                INNER JOIN CECOM_MARCAS ON EQP_MARCA = MAR_ID
                INNER JOIN CECOM_SITUACIONES ON EQP_SITUACION = SIT_LLAVE
                INNER JOIN CECOM_ASIG_EQUIPO ON ASI_EQUIPO = EQP_ID
                INNER JOIN MDEP ON DEP_LLAVE = ASI_DEPENDENCIA
                WHERE ASI_SITUACION= 1 AND ASI_STATUS = 8

                <?php

namespace Model;

class Mantenimiento extends ActiveRecord
{
    public static $tabla = 'cecom_mantto_rep';
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
}