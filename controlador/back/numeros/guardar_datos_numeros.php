<?php 

require_once "../conex.php";
 
class movimineto_numero {
    private $id;
    private $numero;
    private $numero_id;
    private $area_id;
    private $flujo_id;
    private $token;
    private $estado;

    public function __construct ($datos) {
        $this->id = $datos->id;
        $this->numero = $datos->numero;
        $this->numero_id = $datos->numero_id;
        $this->area_id = $datos->area_id;
        $this->flujo_id = $datos->flujo_id;
        $this->token = $datos->token; 
        $this->estado = $datos->estado;
    }

    public function guardar_datos () {
        $conn = new Conexion();
        try {
            if ($this->id == 0) {
                $sql = "INSERT INTO numeros (telefono_id, area, numero, flujo_id, token_api, estado) VALUES (:TELEFONO_ID, :AREA_ID, :NUMERO, :FLUJO_ID, :TOKEN, :ESTADO)";
                $consulta = $conn->prepare($sql);
                $consulta -> bindParam(":TELEFONO_ID", $this->numero_id);
                $consulta -> bindParam(":AREA_ID", $this->area_id);
                $consulta -> bindParam(":NUMERO", $this->numero);
                $consulta -> bindParam(":FLUJO_ID", $this->flujo_id);
                $consulta -> bindParam(":TOKEN", $this->token);
                $consulta -> bindParam(":ESTADO", $this->estado);
                $consulta->execute();
            } else {
                $sql = "UPDATE numeros SET telefono_id = :TELEFONO_ID, area = :AREA_ID, numero = :NUMERO, flujo_id = :FLUJO_ID, token_api = :TOKEN, estado = :ESTADO WHERE numeros.id_numero = :ID";
                $consulta = $conn->prepare($sql);
                $consulta -> bindParam(":ID", $this->id);
                $consulta -> bindParam(":TELEFONO_ID", $this->numero_id);
                $consulta -> bindParam(":AREA_ID", $this->area_id);
                $consulta -> bindParam(":NUMERO", $this->numero);
                $consulta -> bindParam(":FLUJO_ID", $this->flujo_id);
                $consulta -> bindParam(":TOKEN", $this->token);
                $consulta -> bindParam(":ESTADO", $this->estado);
                $consulta->execute();
            }
            return "se guardo correctamente";
        }
        catch(Exception $e){
            echo 'Excepción capturada: '. $e;
        }
    }

    public function extraer_numeros () {
        $conn = new Conexion();
        try {
            if($this->id == 0){
                $sql = "SELECT numeros.id_numero, numeros.telefono_id, numeros.area, numeros.numero, numeros.flujo_id, numeros.estado, area.nombre_area, flujos.nombre_flujo
                FROM numeros INNER JOIN area ON numeros.area = area.id_area INNER JOIN flujos ON numeros.flujo_id = flujos.id_flujo";
                $consulta = $conn->prepare($sql);
                $consulta->execute();
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $sql = "SELECT numeros.id_numero, numeros.telefono_id, numeros.area, numeros.numero, numeros.flujo_id, numeros.token_api, numeros.estado, area.nombre_area, flujos.nombre_flujo
                FROM numeros INNER JOIN area ON numeros.area = area.id_area INNER JOIN flujos ON numeros.flujo_id = flujos.id_flujo WHERE numeros.id_numero = :ID";
                $consulta = $conn->prepare($sql);
                $consulta -> bindParam(":ID", $this->id);
                $consulta->execute();
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            }
            return $resultado;
        }
        catch (Exception $e) {
            echo "Error al extraer numero".$e;
        }
    }

    public function eliminar_numero () {
        $conn = new Conexion();
        try {
            $sql = "DELETE FROM numeros WHERE `numeros`.`id_numero` = :ID";
            $consulta = $conn->prepare($sql);
            $consulta -> bindParam(":ID", $this->id);
            $consulta->execute();
        } catch (\Throwable $th) {
            echo "error al eliminar ".$th;
        }
    }

    public function extraer_area () {
        $conn = new Conexion();
        try {
            $sql = "SELECT * FROM area WHERE area.estado = 0";
            $consulta = $conn->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (\Throwable $th) {
            echo "Error: ".$th;
        }
    }

    public function extraer_flujos () {
        $conn = new Conexion();
        try {
            $sql = "SELECT * FROM flujos WHERE flujos.estado = 0";
            $consulta = $conn->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (\Throwable $th) {
            echo "Error: ".$th;
        }
    }
}
