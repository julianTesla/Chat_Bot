<?php
//DESHABILITAMOS EL MOSTRAR ERRORES
//  ini_set('display_errors', 0);
//  ini_set('display_startup_errors', 0);
//  error_reporting(-1);

//require '../vendor/autoload.php';
require_once "./mensaje_tipo.php";

//use \Axiom\Rivescript\Rivescript;

// Verificación del Webhook
$token = 'INSTITUTOTESLA';
$palabraReto = $_GET['hub_challenge'];
$tokenVerificacion = $_GET['hub_verify_token'];

if ($token === $tokenVerificacion) {  
    echo $palabraReto;
    exit;
}

// Recepción de mensajes
$bandera = 0;
$respuesta = file_get_contents("php://input");
file_put_contents('logs.json', $respuesta);
$respuesta = json_decode($respuesta, true);

if (isset($respuesta['entry'][0]['changes'][0]['value']['messages'][0]['type'])) {
    $tipo_mensaje = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['type'];

    if ($tipo_mensaje === 'text') {
        $bandera = 1;
        $data = new stdClass();
        $mensaje = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
        $data->mensaje = $mensaje;
        $data->tipo_mensaje = 1;
        file_put_contents('text.txt', $mensaje);
   
    } elseif ($tipo_mensaje === 'interactive') {
        $bandera = 2;
        $data = new stdClass();

        if (isset($respuesta['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['list_reply']['id'])) {
            $data->mensaje = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['list_reply']['id'];
            $data->tipo_mensaje = 3;
        } elseif (isset($respuesta['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['button_reply']['id'])) {
            $data->mensaje = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['button_reply']['id'];
            $data->tipo_mensaje = 2;
        }
    }
    
    $data->telefono_id = $respuesta['entry'][0]['changes'][0]['value']['metadata']['phone_number_id'];
    $data->telefono_cliente = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['from'];

    try {
        $buscar_mensaje = new tipo_mensaje();
        if($bandera == 1) {
            $response = $buscar_mensaje->mensaje_texto($data);
        }
        if($bandera == 2){
            $response = $buscar_mensaje->mensaje_interactivo($data);
        }

    } catch (Exception $e) {
        file_put_contents('text.txt', $e);
    }
}