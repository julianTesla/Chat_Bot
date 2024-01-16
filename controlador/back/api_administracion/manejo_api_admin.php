<?php
require_once "buscar_analiticas.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datos = file_get_contents("php://input");
    file_put_contents('logs_numeros.json', $datos);
    $datos = json_decode($datos);

    if($datos->funcion) {
        realizar_accion($datos); 
    }
}

function realizar_accion($datos) { 

    $admin = new analiticas($datos);
    
    switch($datos->funcion) {
        case 'extraer_numeros':
            $resultado = $admin->extraer_numeros();
            header("Content-type: application/json; charset=utf-8");
            echo $resultado;
            break;

        case 'extraer_analiticas':
            $resultado = $admin->extraer_analiticas();
            header("Content-type: application/json");
            echo $resultado;
            break;

        case 'extraer_conversaciones':
            $resultado = $admin->extraer_coneversaciones();
            header("Content-type: application/json");
            echo $resultado;
            break;

        default:
            echo "Error de funcion incorrecta";
            break;
    }
}
