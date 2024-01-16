<?php
require_once('./movimientos_datos.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = file_get_contents("php://input");
    file_put_contents('logs_back.json', $data);
    $data = json_decode($data);
}

switch ($data->funcion){
    
    case 'guardar_mensaje':
       try {
            $mensage_accion = new main($data);
            $datos_opciones = [];
            $cont = 0;

            $mensage_accion->guardar_mensaje();

            if($data->tipo == 2 or $data->tipo == 3) {
                if(isset($data->opciones)) {
                    
                $array_back = $mensage_accion->comprobar_relacion();
                $filtrar_opciones = $mensage_accion->comparar_array($data->opciones, $array_back); 
                
                    foreach($filtrar_opciones->diferencia_back as $eliminar) {
                        $mensage_accion->eliminar_opciones(intval($eliminar));
                    }
                    foreach($filtrar_opciones->diferencia_front as $ingresar) {
                        $datos = new stdClass();
                        $datos->nombre = $ingresar;
                        $datos->encabezado = "";
                        $datos->cuerpo = "";
                        $datos->pie = "";
                        $datos->tipo = 1;
                        $datos->estado = 0;
                        $datos->id_flujo = $data->id_flujo;
                        
                        $ingresar_opciones = new main($datos);
                        $opcion_id = $ingresar_opciones->guardar_mensaje();
                    
                        $datos_opciones[$cont] = $opcion_id;
                        $cont++;
                    }
                    //var_dump($datos_opciones);
                    foreach($datos_opciones as $opciones_id) {
                        $mensage_accion->guardar_opciones(intval($opciones_id));
                    }
                }
            }
       } catch (Exception $e) {
        echo "Excepción capturada: ", $e->getMessage(), "\n";
       }
        break;
    
    case 'modificar_mensaje':
        $mensage_accion = new main($data);
        $mensage_accion->guardar_mensaje();
        break;

    case 'funcion_eliminar_opciones':
        $mensage_accion = new main($data);
        $mensage_accion->eliminar_opciones($param);
        break;

    case 'extraer_mensaje':
        $mensage_accion = new main($data);
        $extraido = $mensage_accion->extraer_mensaje();
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($extraido);
        break;

    case 'guardar_flujo':
        $datos = new stdClass();
        $datos->nombre = "";
        $datos->encabezado = "";
        $datos->cuerpo = "";
        $datos->pie = "";
        $datos->tipo = 1;
        $datos->estado = 0;
        $datos->id_flujo = 0;

        $mensage_accion = new main($datos);
        $mensage_accion->guardar_flujo($data);
        break;

    case 'extraer_flujos':
        $datos = new stdClass();
        $datos->nombre = 1;
        $datos->encabezado = "";
        $datos->cuerpo = "";
        $datos->pie = "";
        $datos->tipo = 1;
        $datos->estado = 0;
        $datos->id_flujo = 0;

        $mensage_accion = new main($datos);
        $respuesta = $mensage_accion->extraer_flujo($data);

        header("Content-type: application/json; charset=utf-8");
        echo json_encode($respuesta);
        break;
        
    case 'extraer_mensaje_principal':
        $datos = new stdClass();
        $datos->nombre = 1;
        $datos->encabezado = "";
        $datos->cuerpo = "";
        $datos->pie = "";
        $datos->tipo = 1;
        $datos->estado = 0;
        $datos->id_flujo = 0;

        $mensage_accion = new main($datos);
        $respuesta = $mensage_accion->extraer_mensaje_principal($data);

        header("Content-type: application/json; charset=utf-8");
        echo json_encode($respuesta);
        break;
        
    case 'guardar_opcion':
        $mensage_accion = new main($data);
        $mensage_accion->guardar_mensaje();

            $datos = new stdClass();
            $datos->nombre = "Nueva Opcion";
            $datos->encabezado = "";
            $datos->cuerpo = "";
            $datos->pie = "";
            $datos->tipo = 1;
            $datos->estado = 0;
            $datos->id_flujo = $data->id_flujo;
        
        $ingresar_opciones = new main($datos);
        $opcion_id = $ingresar_opciones->guardar_mensaje();

        $mensage_accion->guardar_opciones($opcion_id);
        break;
    default:
    echo '{"Error":"funcion Incorrecta"}';
}