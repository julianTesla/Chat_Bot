<?php
require_once "../conex.php";

    function extraer_mensaje_principal ($id) {
        $conn = new Conexion();
        $sql = "SELECT msg_armados.id_msg, flujos.nombre_flujo FROM flujos INNER JOIN msg_armados ON flujos.id_flujo = msg_armados.flujo_id AND flujos.id_flujo = :FLUJO_ID ORDER BY msg_armados.id_msg ASC LIMIT 1";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam(":FLUJO_ID", $id);
        $consulta -> execute();
        $respuesta = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $filas = $consulta->rowCount();
        if($filas >= 1) {
         return $respuesta[0]['id_msg'];
        } 
    }