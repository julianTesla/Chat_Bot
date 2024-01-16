<?php
header('Access-Control-Allow-Origin: *');
header("Content-type: application/json; charset=utf-8");
require_once "../conex.php";
include "buscar_flujo.php";

if($_GET['id']) {
    $id = $_GET['id'];
    $id_msg = extraer_mensaje_principal($id);

    $mensaje = extraer_mensaje($id_msg);
    
    // Verificar si hay un mensaje antes de convertirlo a JSON
    if ($mensaje) {
        $mensaje = json_encode($mensaje);
        echo $mensaje;
        file_put_contents("mensaje.json", $mensaje);
    } else {
        echo "{No se encontró un mensaje con proporcionado.}";
    }
}




function extraer_mensaje($id) {
    $conn = new Conexion();

    $sql = "SELECT id_msg, nombre_msg, encabezado_msg, cuerpo_msg, pie_msg FROM msg_armados WHERE msg_armados.id_msg = :ID AND estado_msg = 0";
    $consulta = $conn->prepare($sql);
    $consulta->bindParam(":ID", $id, PDO::PARAM_INT);
    $consulta->execute();
    $mensaje = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$mensaje) {
        return null;
    }

    $opciones = buscar_opciones($id);

    // Verificar si hay opciones antes de agregar el campo 'opciones' al mensaje
    if (!empty($opciones)) {
        // Recursivamente obtener mensajes para cada opción
        $mensaje['opciones'] = array_map(function ($opcion) {
            return extraer_mensaje($opcion['opciones_id']);
        }, $opciones);
    }

    $conn = null;

    return $mensaje;
}

function buscar_opciones($id) {
    $conn = new Conexion();

    $sql = "SELECT opciones_id, nombre_msg FROM relaciones JOIN msg_armados ON relaciones.opciones_id = msg_armados.id_msg WHERE relaciones.msg_id = :ID AND relaciones.estado = 0 AND msg_armados.estado_msg = 0";
    $consulta = $conn->prepare($sql);
    $consulta->bindParam(':ID', $id);
    $consulta->execute();
    $opciones = $consulta->fetchAll(PDO::FETCH_ASSOC);

    $conn = null;

    return $opciones;
}