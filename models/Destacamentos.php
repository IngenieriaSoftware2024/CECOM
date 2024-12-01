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
        $sql = "SELECT UBI_ID, UBI_NOMBRE FROM CECOM_DEST_BRGS WHERE UBI_DEPENDENCIA  = '$dep_llave' AND UBI_SITUACION = 1"; //VERIFICAR ESTO

        return self::fetchArray($sql);
    }

    public static function DestacamentosActivosGeneral()
    {
        $query = "SELECT * FROM CECOM_DEST_BRGS WHERE UBI_SITUACION = 1";

        if (isset($_SESSION['CECOM_USUARIO']) && !empty($_SESSION['dep_llave'])) {
            $dependencia = intval($_SESSION['dep_llave']);
            $query .= " AND ubi_dependencia = $dependencia";
        }

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

        // Base de la consulta
        $sql = "SELECT 
                    UBI_LATITUD, 
                    UBI_LONGITUD, 
                    COUNT(ASI_EQUIPO) AS cantidad_equipos 
                FROM 
                    CECOM_ASIG_EQUIPO
                INNER JOIN 
                    CECOM_DEST_BRGS ON UBI_ID = ASI_DESTACAMENTO
                INNER JOIN 
                    CECOM_EQUIPO ON EQP_ID = ASI_EQUIPO
                WHERE 
                    EQP_CLASE IN ($listadoIds) 
                    AND ASI_SITUACION = 1 AND UBI_SITUACION = 1"; //VERIFICAR ESTA CONSULTA


        if (isset($_SESSION['CECOM_USUARIO']) && !empty($_SESSION['dep_llave'])) {
            $dependencia = intval($_SESSION['dep_llave']);
            $sql .= " AND ubi_dependencia = $dependencia";
        }


        $sql .= " GROUP BY UBI_LATITUD, UBI_LONGITUD";

        return self::fetchArray($sql);
    }


    public static function TodosDestacamentosActivos()
    {
        $sql = "SELECT UBI_ID, UBI_NOMBRE FROM CECOM_DEST_BRGS WHERE UBI_SITUACION = 1";


        if (isset($_SESSION['CECOM_USUARIO']) && !empty($_SESSION['dep_llave'])) {
            $dependencia = intval($_SESSION['dep_llave']);
            $sql .= " AND ubi_dependencia = $dependencia";
        }


        return self::fetchArray($sql);
    }


    //  FUNCIONES PARA MOSTRAR EN LA PESTANA REPORTES

    public static function InformacionGeneral($idEquipo, $Status)
    {

        $situacion = ($Status == 7) ? 0 : 1;
    
  
        $sql = "SELECT EQP_ID, EQP_SERIE AS SERIE, clase.CAR_NOMBRE AS CLASE, gama.CAR_NOMBRE AS GAMA, 
                    MAR_DESCRIPCION AS MARCA, 
                    situacion_status.SIT_DESCRIPCION AS ESTATUS, 
                    estado.SIT_DESCRIPCION AS ESTADO, 
                    UBI_NOMBRE ";
    

        if ($Status != 7) {
            $sql .= ", TRIM(gra_desc_lg) || ' DE ' || TRIM(arm_desc_lg) AS GRADO, 
                       TRIM(per_nom1) || ' ' || TRIM(per_nom2) || ' ' || TRIM(per_ape1) || ' ' || TRIM(per_ape2) AS RESPONSABLE, 
                       PER_CATALOGO ";
        }
    

        $sql .= "FROM CECOM_EQUIPO
                INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP_CLASE
                INNER JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP_GAMA
                INNER JOIN CECOM_MARCAS ON EQP_MARCA = MAR_ID
                INNER JOIN CECOM_ASIG_EQUIPO ON ASI_EQUIPO = EQP_ID
                INNER JOIN CECOM_SITUACIONES AS situacion_status ON CECOM_ASIG_EQUIPO.ASI_STATUS = situacion_status.SIT_LLAVE
                INNER JOIN CECOM_SITUACIONES AS estado ON estado.SIT_LLAVE = EQP_ESTADO
                LEFT JOIN CECOM_DEST_BRGS ON UBI_ID = ASI_DESTACAMENTO ";
    

        if ($Status != 7) {
            $sql .= "LEFT JOIN MPER ON PER_PLAZA = ASI_RESPONSABLE
                     INNER JOIN grados ON gra_codigo = per_grado
                     INNER JOIN armas ON arm_codigo = per_arma ";
        }
    

        $sql .= "WHERE ASI_EQUIPO = $idEquipo AND ASI_SITUACION = $situacion";
    
        return self::fetchFirst($sql);
    }
    
    

    public static function AccesorioEquipo($idEquipo)
    {
    

        $sql = "SELECT ASIG_EQUIPO, ACC_NOMBRE, ASIG_CANTIDAD, SIT_DESCRIPCION FROM cecom_asig_accesorios
                INNER JOIN CECOM_ACCESORIOS ON ACC_ID = ASIG_ACCESORIO
                INNER JOIN CECOM_SITUACIONES ON SIT_LLAVE = ASIG_ESTADO
                WHERE ASIG_EQUIPO = $idEquipo AND ASIG_SITUACION = 1";

        return self::fetchArray($sql);
    }

    public static function HistorialMovimientos($idEquipo)
    {

        $sql = "SELECT DEP_DESC_MD AS DEPENDENCIA, UBI_NOMBRE, ASI_FECHA, ASI_MOTIVO, SIT_DESCRIPCION FROM CECOM_ASIG_EQUIPO
                LEFT JOIN MDEP ON DEP_LLAVE  = ASI_DEPENDENCIA
                LEFT JOIN CECOM_DEST_BRGS ON UBI_ID = ASI_DESTACAMENTO
                INNER JOIN CECOM_SITUACIONES ON SIT_LLAVE = ASI_STATUS
                WHERE ASI_EQUIPO = $idEquipo
                ORDER BY ASI_ID";

        return self::fetchArray($sql);
    }

    public static function HistorialMantenimientos($idEquipo)
    {
        $sql = "SELECT 
                R.REP_ID,

                MAX(R.REP_FECHA_ENTRADA) AS REP_FECHA_ENTRADA,
                MAX(R.REP_FECHA_SALIDA) AS REP_FECHA_SALIDA,
                MAX(R.REP_DESC) AS REP_DESC,
                
                -- Descripciones de los estados
                MAX(SIT_ANT.SIT_DESCRIPCION) AS REP_ESTADO_ANT_DESC,
                MAX(SIT_ACT.SIT_DESCRIPCION) AS REP_ESTADO_ACTUAL_DESC,
                MAX(SIT_STATUS.SIT_DESCRIPCION) AS REP_STATUS_DESC,
                
                MAX(R.REP_OBS) AS REP_OBS,
                
                EQP.EQP_ID,
                -- Concatenación de nombre completo para el responsable
                TRIM(GRA.GRA_DESC_LG) || ' DE ' || TRIM(ARM.ARM_DESC_LG) || ' ' || TRIM(PER.PER_NOM1) || ' ' || TRIM(PER.PER_NOM2) || ' ' || TRIM(PER.PER_APE1) || ' ' || TRIM(PER.PER_APE2) AS RESPONSABLE_NOMBRE_COMPLETO,
                
                -- Concatenación de nombre completo para quien entregó
                TRIM(GRA1.GRA_DESC_LG) || ' DE ' || TRIM(ARM1.ARM_DESC_LG) || ' ' || TRIM(PER1.PER_NOM1) || ' ' || TRIM(PER1.PER_NOM2) || ' ' || TRIM(PER1.PER_APE1) || ' ' || TRIM(PER1.PER_APE2) AS ENTREGADO_NOMBRE_COMPLETO,
                
                -- Concatenación de nombre completo para quien recibió
                TRIM(GRA2.GRA_DESC_LG) || ' DE ' || TRIM(ARM2.ARM_DESC_LG) || ' ' || TRIM(PER2.PER_NOM1) || ' ' || TRIM(PER2.PER_NOM2) || ' ' || TRIM(PER2.PER_APE1) || ' ' || TRIM(PER2.PER_APE2) AS RECIBIDO_NOMBRE_COMPLETO,
                
                -- Concatenando clase, marca, gama y serie en un solo campo
                TRIM(clase.CAR_NOMBRE) || '  ' || TRIM(MARC.MAR_DESCRIPCION) || '  ' || TRIM(gama.CAR_NOMBRE) || ' serie:  ' || TRIM(EQP.EQP_SERIE) AS EQUIPO_DESCRIPCION,
                
                -- Dependencia obtenida desde CECOM_ASIG_EQUIPO
                TRIM(DEP.DEP_DESC_MD) AS DEPENDENCIA
            FROM 
                CECOM_MANTTO_REP R
                -- Información del equipo
                INNER JOIN CECOM_EQUIPO EQP ON EQP.EQP_ID = R.REP_EQUIPO
                INNER JOIN CECOM_CARACTERISTICAS AS clase ON clase.CAR_ID = EQP.EQP_CLASE
                INNER JOIN CECOM_CARACTERISTICAS AS gama ON gama.CAR_ID = EQP.EQP_GAMA
                INNER JOIN CECOM_MARCAS MARC ON EQP.EQP_MARCA = MARC.MAR_ID
                INNER JOIN CECOM_SITUACIONES ON EQP.EQP_ESTADO = CECOM_SITUACIONES.SIT_LLAVE
                -- Información sobre la asignación del equipo para obtener la dependencia
                INNER JOIN CECOM_ASIG_EQUIPO ON CECOM_ASIG_EQUIPO.ASI_EQUIPO = EQP.EQP_ID
                INNER JOIN MDEP DEP ON DEP.DEP_LLAVE = CECOM_ASIG_EQUIPO.ASI_DEPENDENCIA
                -- Información sobre el responsable
                LEFT JOIN MPER PER ON PER.PER_CATALOGO = R.REP_RESPONSABLE
                LEFT JOIN ARMAS ARM ON ARM.ARM_CODIGO = PER.PER_ARMA
                LEFT JOIN GRADOS GRA ON GRA.GRA_CODIGO = PER.PER_GRADO
                -- Información sobre quien entregó
                LEFT JOIN MPER PER1 ON PER1.PER_CATALOGO = R.REP_ENTREGO
                LEFT JOIN ARMAS ARM1 ON ARM1.ARM_CODIGO = PER1.PER_ARMA
                LEFT JOIN GRADOS GRA1 ON GRA1.GRA_CODIGO = PER1.PER_GRADO
                -- Información sobre quien recibió
                LEFT JOIN MPER PER2 ON PER2.PER_CATALOGO = R.REP_RECIBIDO
                LEFT JOIN ARMAS ARM2 ON ARM2.ARM_CODIGO = PER2.PER_ARMA
                LEFT JOIN GRADOS GRA2 ON GRA2.GRA_CODIGO = PER2.PER_GRADO
                
                -- Información de los estados (JOIN con CECOM_SITUACIONES)
                LEFT JOIN CECOM_SITUACIONES SIT_ANT ON SIT_ANT.SIT_LLAVE = R.REP_ESTADO_ANT
                LEFT JOIN CECOM_SITUACIONES SIT_ACT ON SIT_ACT.SIT_LLAVE = R.REP_ESTADO_ACTUAL
                LEFT JOIN CECOM_SITUACIONES SIT_STATUS ON SIT_STATUS.SIT_LLAVE = R.REP_STATUS
            WHERE REP_EQUIPO = $idEquipo
            GROUP BY 
                R.REP_ID, EQP.EQP_ID, clase.CAR_NOMBRE, MARC.MAR_DESCRIPCION, gama.CAR_NOMBRE, EQP.EQP_SERIE, DEP.DEP_DESC_MD,
                -- Agregamos las columnas concatenadas en el GROUP BY
                GRA.GRA_DESC_LG, ARM.ARM_DESC_LG, PER.PER_NOM1, PER.PER_NOM2, PER.PER_APE1, PER.PER_APE2,
                GRA1.GRA_DESC_LG, ARM1.ARM_DESC_LG, PER1.PER_NOM1, PER1.PER_NOM2, PER1.PER_APE1, PER1.PER_APE2,
                GRA2.GRA_DESC_LG, ARM2.ARM_DESC_LG, PER2.PER_NOM1, PER2.PER_NOM2, PER2.PER_APE1, PER2.PER_APE2,
                SIT_ANT.SIT_DESCRIPCION, SIT_ACT.SIT_DESCRIPCION, SIT_STATUS.SIT_DESCRIPCION

            ORDER BY R.REP_ID ASC";

        return self::fetchArray($sql);
    }
}
