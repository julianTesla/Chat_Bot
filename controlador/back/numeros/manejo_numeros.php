<?php
header('Access-Control-Allow-Origin: http://localhost'); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

require_once "./guardar_datos_numeros.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datos = file_get_contents("php://input");
    file_put_contents('logs_numeros.json', $datos);
    $datos = json_decode($datos);
    
    if($datos->funcion) {
        realizar_accion($datos);
    }
}

function realizar_accion ($datos) {

    $movimiento = new movimineto_numero($datos);
    switch ($datos->funcion) {
        case "guardar_numero":
            $resultado = $movimiento->guardar_datos();
            echo json_encode($resultado);
            break;
        
        case "extraer_numeros":
            $resultado = $movimiento->extraer_numeros();
            $resultado = json_encode($resultado);
            echo $resultado;
            break;
        
        case "eliminar_numero":
            $resultado = $movimiento->eliminar_numero();
            $resultado = json_encode($resultado);
            echo $resultado;
            break;

        case "extraer_area":
            $resultado = $movimiento->extraer_area();
            $resultado = json_encode($resultado);
            echo $resultado;
            break;

        case "extraer_flujos":
            $resultado = $movimiento->extraer_flujos();
            $resultado = json_encode($resultado);
            echo $resultado;
            break;

        default:
            echo '{"respuesta":"No se reconoce la funcion"}';
            break;
    }
}


