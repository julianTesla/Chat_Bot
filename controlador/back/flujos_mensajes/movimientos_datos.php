<?php
require_once '../conex.php';

class main {

    /**
     * @var string El nombre del mensaje crado.
     */  
    public $nombre;
    
    /**
     * @var string encavezado del mensaje
     */
    public $encabezado;
    
    /**
     * @var string cuerpo de mensaje
     */
    public $cuerpo;

    /**
     * @var string pie de mensaje
     */
    public $pie;

    /**
     * @var int tipo de mensaje
     */
    public $tipo;

    /**
     * @var bool etado de mensaje
     */
    public $estado;

    /**
     * @var int ID de mensaje
     */
    public $id;

    /**
     * @var int id del flujo al que pertenese el mensaje
     */
    public $flujo_id;

    /**
     * @param stdclass $data contiene los datos de los mensages
     */
    public function __construct($data) {
        $this->nombre = $data->nombre;
        $this->encabezado = $data->encabezado;
        $this->cuerpo = $data->cuerpo;
        $this->pie = $data->pie;
        $this->tipo = $data->tipo;
        $this->estado = $data->estado;
        $this->flujo_id = $data->id_flujo;

        //si se recibe id guardarlo, si no,
        if(isset($data->id)) {
            $this->id = $data->id;
        }
    }
    

    public function guardar_mensaje () {
        $conn = new Conexion();
        try {
            if($this->id != 0) {
            //actualizar el registro del usuario en la base de datos.
                echo "actualizar";
                $sql = "UPDATE `msg_armados` SET `nombre_msg` = :NOMBRE, `encabezado_msg` = :ENCABEZADO, `cuerpo_msg` = :CUERPO, `pie_msg` = :PIE, `tipo_msg` = :TIPO, `estado_msg` = :ESTADO, `flujo_id` = :FLUJO_ID WHERE `msg_armados`.`id_msg` = :ID";
                $consulta = $conn->prepare($sql);
                $consulta -> bindParam(':NOMBRE', $this->nombre);
                $consulta -> bindParam(':ENCABEZADO', $this->encabezado);
                $consulta -> bindParam(':CUERPO', $this->cuerpo);
                $consulta -> bindParam(':PIE', $this->pie);
                $consulta -> bindParam(':TIPO', $this->tipo);
                $consulta -> bindParam(':ESTADO', $this->estado);
                $consulta -> bindParam(':ID', $this->id);
                $consulta -> bindParam(':FLUJO_ID', $this->flujo_id);
                $consulta -> execute();
                $ultimoID = $conn->lastInsertId();
                $respuesta= $ultimoID;
                
            } else {
                echo "insertar";
                $sql = "INSERT INTO `msg_armados` (`nombre_msg`, `encabezado_msg`, `cuerpo_msg`, `pie_msg`, `tipo_msg`, `estado_msg`, `flujo_id`) VALUES (:NOMBRE, :ENCABEZADO, :CUERPO, :PIE, :TIPO, :ESTADO, :FLUJO_ID)";
                    $consulta = $conn->prepare($sql);
                    $consulta -> bindParam(':NOMBRE', $this->nombre);
                    $consulta -> bindParam(':ENCABEZADO', $this->encabezado);
                    $consulta -> bindParam(':CUERPO', $this->cuerpo);
                    $consulta -> bindParam(':PIE', $this->pie);
                    $consulta -> bindParam(':TIPO', $this->tipo);
                    $consulta -> bindParam(':ESTADO', $this->estado);
                    $consulta -> bindParam(':FLUJO_ID', $this->flujo_id);
                    $consulta -> execute();
                    $ultimoID = $conn->lastInsertId();
                    $this->id = $ultimoID;
                    $respuesta= $ultimoID;
            }

        } catch(PDOException $e) {
            echo "Error: " . $e;
            $respuesta = false;
        }

        $conn = null;
        return $respuesta;
    }

   
    private function buscar_opciones() {
        $conn = new Conexion();

        $sql = "SELECT opciones_id, msg_armados.nombre_msg FROM relaciones JOIN msg_armados ON relaciones.opciones_id = msg_armados.id_msg WHERE relaciones.msg_id = :ID AND relaciones.estado = 0 AND msg_armados.estado_msg = 0";
            $consulta = $conn->prepare($sql);
            $consulta -> bindParam(':ID', $this->id);
            $consulta -> execute();   
            $respuesta = $consulta -> fetchAll(PDO::FETCH_ASSOC);        
            
            $conn = null;
        return $respuesta;  
    }


    public function extraer_mensaje() {
        $respuesta = new stdClass();
        $conn = new Conexion();
    
        $sql = "SELECT id_msg, nombre_msg, encabezado_msg, cuerpo_msg, pie_msg, tipo_msg, flujo_id FROM msg_armados WHERE msg_armados.id_msg = :ID AND estado_msg = 0";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam(":ID", $this->id, PDO::PARAM_INT);
        $consulta -> execute();
        $mensaje = $consulta -> fetchAll(PDO::FETCH_ASSOC); // Almacenar el resultado en $mensaje, no en $respuesta
        $opciones = $this->buscar_opciones();
        //Asignar resultados a propiedades del objeto $respuesta
        $respuesta -> mensaje = $mensaje;
        $respuesta -> opciones = $opciones;
        $conn = null;
        return $respuesta;
    }


    public function guardar_opciones ($opcion_id) {
        $conn = new Conexion();

        $sql = "INSERT INTO relaciones (msg_id, opciones_id) VALUES (:MSG_ID, :OPCIONES_ID)";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam(":MSG_ID", $this->id);
        $consulta -> bindParam(":OPCIONES_ID", $opcion_id, PDO::PARAM_INT);
        $consulta -> execute();

        $conn = null;
        return "opcion insertada correcamente";
    }
    
    
    public function eliminar_opciones ($id_opcion) {
        $conn = new Conexion();

        $extraer = "SELECT id_relacion FROM relaciones WHERE relaciones.msg_id = :ID AND relaciones.opciones_id = :OPCION AND relaciones.estado = 0";
        $ejecuatar = $conn->prepare($extraer);
        $ejecuatar -> bindParam(":ID", $this->id, PDO::PARAM_INT );
        $ejecuatar -> bindParam(":OPCION", $id_opcion);
        $ejecuatar -> execute();
        $respuesta = $ejecuatar -> fetchColumn();
        $respuesta = intval($respuesta);
        
        $sql = "UPDATE `relaciones` SET `estado` = 1 WHERE `relaciones`.`id_relacion` = :ID_OPCION";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam(":ID_OPCION", $respuesta, PDO::PARAM_INT);
        $consulta -> execute();

       $this->eliminar_mensajes($id_opcion);
        
        $conn = null;
        return $respuesta;
    }
    

    public function eliminar_mensajes ($id_mensaje) {
        $conn = new Conexion();

        $sql = "UPDATE `msg_armados` SET `estado_msg` = '1' WHERE `msg_armados`.`id_msg` = :ID_MENSAJE";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam(":ID_MENSAJE", $id_mensaje, PDO::PARAM_INT);
        $consulta -> execute();
    }


    public function comprobar_relacion () {

        $conn = new Conexion();

        $sql = "SELECT relaciones.opciones_id FROM relaciones WHERE relaciones.estado = 0 AND relaciones.msg_id = :OPCION_ID";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam(":OPCION_ID", $this->id, PDO::PARAM_INT);
        $consulta -> execute();
        $respuesta = $consulta -> fetchAll(PDO::FETCH_NUM);
        
        $conn = null;
        return $respuesta;
    }

    
    public function comparar_array ($array_front, $array_back) {
        
        $devolver = new stdClass();

        $unidimensionalArray = array_map(function($subarray) {
            return $subarray[0];
        }, $array_back);

        $diferencia1 = array_diff($unidimensionalArray, $array_front);
        $diferencia2 = array_diff($array_front, $unidimensionalArray);
        $devolver -> diferencia_back = $diferencia1;
        $devolver -> diferencia_front = $diferencia2;
        
        return $devolver;    
    }


    public function guardar_flujo ($data) {
        
        $conn = new Conexion();
        try {
            if($data->id_flujo === 0) {
                var_dump($data);
                $sql="INSERT INTO flujos (nombre_flujo, palabra_clave, estado) VALUES (:NOMBRE_FLUJO, :PALABRA_CLAVE, :ESTADO)";
                $consulta = $conn->prepare($sql);
                $consulta -> bindParam(":NOMBRE_FLUJO", $data->nombre_flujo);
                $consulta -> bindParam(":PALABRA_CLAVE", $data->palabra_clave);
                $consulta -> bindParam(":ESTADO", $data->estado);
                $consulta ->execute();

            } else {
                $sql = "UPDATE `flujos` SET nombre_flujo = :NOMBRE_FLUJO, palabra_clave = :PALABRA_CLAVE, estado = :ESTADO WHERE `flujos`.`id_flujo` = :ID_FLUJO";
                $consulta = $conn->prepare($sql);
                $consulta -> bindParam(":ID_FLUJO", $data->id_flujo);
                $consulta -> bindParam(":NOMBRE_FLUJO", $data->nombre_flujo);
                $consulta -> bindParam(":PALABRA_CLAVE", $data->palabra_clave);
                $consulta -> bindParam(":ESTADO", $data->estado);
                $consulta -> execute(); 
            }
       
        } catch (Exception $e){
            echo "Error al insertar el flujo".$e;
        }
    }

    public function extraer_flujo($data) {
        $conn = new Conexion();
        try {
       
            $id = $data->id_flujo;
            if( $id == 0) {
                $sql = "SELECT * FROM `flujos` WHERE estado = 0";
                $consulta = $conn->prepare($sql);
                $consulta->execute();
                $respuesta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $sql = "SELECT * FROM `flujos` WHERE id_flujo = :ID AND estado = 0";
                $consulta = $conn->prepare($sql);
                $consulta -> bindParam(":ID", $data->id_flujo, PDO::PARAM_INT);
                $consulta->execute();
                $respuesta = $consulta->fetchAll(PDO::FETCH_ASSOC);
            }

            $conn = null;
            return $respuesta;
        } catch (Exception $e) {
            echo "ERROR: ".$e; 
        }
    }

    function extraer_mensaje_principal ($data) {
        $conn = new Conexion();
        try{
            $sql="SELECT id_msg, nombre_msg FROM `msg_armados` WHERE flujo_id = :ID ORDER BY `id_msg` ASC LIMIT 1";
            $consulta=$conn->prepare($sql);
            $consulta->bindparam(':ID',$data->id_flujo,PDO::PARAM_INT);
            $consulta->execute();
            $resultado =$consulta->fetchAll(PDO::FETCH_ASSOC);
            $filas = $consulta->rowCount();
            
            if($filas > 0) {
                $conn = null;
                return $resultado;
            } else {
                $respuesta =array(
                    array(
                        "id_msg" => 0,
                        "nombre_msg" => "Sin nombre"
                    )
                );
                $conn = null;
                return $respuesta;
            }
        }
        catch (Exception $e){
            $resultado = "No se pudo realizar la consulta".$e;
        }
    }
}