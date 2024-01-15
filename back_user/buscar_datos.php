<?php

require_once "../Admin/back/conex.php";


class buscar_datos {
    
    public $telefono_id;
    public $telefono_cliente;
    public $mensaje_recibido;
    public $tipo_mensaje;
    public $id_cliente;
    public $mensaje_enviado;
    public $id_asistente;
    public $flujo_id;
    public $token;

    public function __construct($data) {
        $this->telefono_id = $data->telefono_id;
        $this->telefono_cliente = $data->telefono_cliente;
        $this->mensaje_recibido = $data->mensaje;
        $this->tipo_mensaje = $data->tipo_mensaje;
    }

    public function verifcar_telefono_id () {
        $conn = new Conexion();

        $sql="SELECT id_numero, flujo_id, token_api, estado FROM numeros WHERE numeros.telefono_id = :TELEFONO_ID AND estado = 0";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam("TELEFONO_ID",$this->telefono_id);
        $consulta->execute();
        $devolvio = $consulta->rowCount();

        if($devolvio >= 1) {
            $id_asistente = $consulta->fetchAll();
            $this->id_asistente = $id_asistente[0]['id_numero'];
            $this->flujo_id = $id_asistente[0]['flujo_id'];
            $this->token = $id_asistente[0]['token_api'];
            $respuesta = true;
        }
        else{
            $respuesta = false;
        }
        return $respuesta;
    }

    private function guardar_telefono_cliente () {
        $conn = new Conexion();

        $sql = "INSERT INTO numeros_usuarios (numero) VALUES (:NUMERO)";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam(':NUMERO',$this->telefono_cliente, PDO::PARAM_INT);
        $consulta ->execute();
        $ultimoID = $conn->lastInsertId();
        $this->id_cliente = $ultimoID;
    }

    public function verificar_telefono_cliente () {
        $conn = new Conexion();
        try {
            $sql= "SELECT id_usuario FROM numeros_usuarios WHERE numero = :NUMERO";
            $consulta = $conn->prepare($sql);
            $consulta -> bindParam(':NUMERO',$this->telefono_cliente, PDO::PARAM_INT);
            $consulta -> execute();
            $filas = $consulta -> rowCount();
            
            if ($filas >0 ) {
                $respuesta = $consulta -> fetchAll();
                $this->id_cliente = $respuesta[0]['id_usuario'];
            }
            else {
                $this->guardar_telefono_cliente();
            }
        }
        catch(PDOException $e) { echo $e; }
    }


    public function guardar_interacion () {
        $conn = new Conexion();

        try {
            
            $sql = "INSERT INTO interaccion (numero_user, mensaje_recibido, id_asistente, mensaje_enviado_id) 
            VALUES (:NUMERO_USER, :MENSAJE_RECIBIDO, :ID_ASISTENTE, :MENSAJE_ENVIADO_ID)";
            $consulta = $conn->prepare($sql);
            $consulta -> bindParam(":NUMERO_USER", $this->id_cliente);
            $consulta -> bindParam(":MENSAJE_RECIBIDO", $this->mensaje_recibido);
            $consulta -> bindParam(":ID_ASISTENTE", $this->id_asistente);
            $consulta -> bindParam(":MENSAJE_ENVIADO_ID", $this->mensaje_enviado);
            $consulta -> execute();
        }
        catch(PDOException $e){echo $e;}
    }


    public function buscar_ultimo_registro () {
        $conn = new Conexion();
        try{
            $sql = "SELECT interaccion.numero_user, interaccion.mensaje_enviado_id, msg_armados.tipo_msg 
            FROM interaccion, numeros_usuarios, msg_armados WHERE numeros_usuarios.id_usuario = interaccion.numero_user 
            AND numeros_usuarios.numero = :NUMERO_CLIENTE AND msg_armados.id_msg = interaccion.mensaje_enviado_id 
            AND msg_armados.flujo_id = :FLUJO_ID ORDER BY id_interaccion DESC LIMIT 1";

            $consulta = $conn->prepare($sql);
            $consulta -> bindParam(":NUMERO_CLIENTE", $this->telefono_cliente);
            $consulta -> bindParam(":FLUJO_ID", $this->flujo_id);
            $consulta->execute();
            $filas = $consulta->rowCount();

            $texto = $this->mensaje_recibido;
            if ($filas > 0) {
                $resultado = $consulta->fetchAll();
                if ($resultado[0]['tipo_msg'] === 1) {
                    
                    $this->mensaje_recibido = $resultado[0]['mensaje_enviado_id'];
                    $opciones = $this->buscar_opciones();

                    if (count($opciones) > 0) {
                        foreach ($opciones as $valor) {
                            if (mb_strtolower($valor['nombre_msg'], 'UTF-8') == mb_strtolower($texto, 'UTF-8')) {
                                echo $this->mensaje_recibido = $valor['opciones_id'];
                                break;
                            }
                        }
                    } //else {
                        //$this->extraer_mensaje_principal();
                    //}

                } else{
                    $this->extraer_mensaje_principal();
                }
            }else {
                $this->extraer_mensaje_principal();
            }
        }
        catch (PDOException $e){
            echo $e;
            file_put_contents("error.txt", $e);    
        }
    }


    public function devolver_mensaje () {
                
        $respuesta = new stdClass();
        $conn = new Conexion();

        $sql = "SELECT id_msg, nombre_msg, encabezado_msg, cuerpo_msg, pie_msg, tipo_msg, flujo_id 
        FROM msg_armados WHERE msg_armados.id_msg = :ID AND estado_msg = 0 AND flujo_id = :ID_FLUJO";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam(":ID", $this->mensaje_recibido);
        $consulta -> bindParam("ID_FLUJO", $this->flujo_id);
        $consulta -> execute();
        $mensaje = $consulta -> fetchAll(PDO::FETCH_ASSOC); // Almacenar el resultado en $mensaje, no en $respuesta
        
        $opciones = $this->buscar_opciones();
        
        //Asignar resultados a propiedades del objeto $respuesta
        $respuesta -> mensaje = $mensaje;
        $respuesta -> opciones = $opciones;
    
        $conn = null;
        return $respuesta;
    }
    private function buscar_opciones () {
        $conn = new Conexion();

        $sql = "SELECT opciones_id, msg_armados.nombre_msg 
        FROM relaciones JOIN msg_armados ON relaciones.opciones_id = msg_armados.id_msg WHERE relaciones.msg_id = :ID 
        AND relaciones.estado = 0 AND msg_armados.estado_msg = 0";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam(':ID', $this->mensaje_recibido);
        $consulta -> execute();
        $respuesta = $consulta -> fetchAll(PDO::FETCH_ASSOC);        
        
        $conn = null;
        return $respuesta;  
    }


    private function extraer_palabra_clave () {
        $conn = new Conexion();
        try {
            $sql = "SELECT * FROM flujos WHERE id_flujo = :ID_FLUJO";
            $consulta = $conn->prepare($sql);
            $consulta -> bindParam(":ID_FLUJO", $this->flujo_id);
            $consulta -> execute();
            $respuesta = $consulta->fetchAll(PDO::FETCH_ASSOC);

            return $respuesta[0]['palabra_clave'];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function comparar_palabra_clave () {
        $cadena_bd = $this->extraer_palabra_clave();
        $cadena_msg = $this->mensaje_recibido;

        if (mb_strtolower($cadena_bd, 'UTF-8') == mb_strtolower($cadena_msg, 'UTF-8')) {
            return true;
        } else {
            return false;
        }
    }

    public function extraer_mensaje_principal () {
        $conn = new Conexion();
        $sql = "SELECT msg_armados.id_msg FROM flujos INNER JOIN msg_armados ON flujos.id_flujo = msg_armados.flujo_id AND flujos.id_flujo = :FLUJO_ID ORDER BY msg_armados.id_msg ASC LIMIT 1";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam(":FLUJO_ID", $this->flujo_id);
        $consulta -> execute();
        $respuesta = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $filas = $consulta->rowCount();
        if($filas >= 1) {
            $this->mensaje_recibido = $respuesta[0]['id_msg'];
        }
    }
}
