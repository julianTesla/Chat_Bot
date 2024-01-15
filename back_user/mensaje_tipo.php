<?php
require_once "./buscar_datos.php";
require_once "./envio_mensaje.php";

class tipo_mensaje {

    function mensaje_interactivo ($data) {

        $extraer_datos = new buscar_datos($data);
        
        $verificar = $extraer_datos->verifcar_telefono_id();

        if($verificar != false) {
            $resultado = $extraer_datos->devolver_mensaje();
           // echo $extraer_datos->mensaje_recibido.$extraer_datos->flujo_id;
    
            $enviar = new envio_mensaje($resultado, $data->telefono_cliente, $extraer_datos->telefono_id, $extraer_datos->token);
            $enviar->enviar_mensaje();

            $extraer_datos->mensaje_enviado = $resultado->mensaje[0]['id_msg'];
            $extraer_datos->verificar_telefono_cliente();
            $extraer_datos->guardar_interacion();
        }
    }


    function mensaje_texto ($data) {
        
        $extraer_datos = new buscar_datos($data);

        $verificar = $extraer_datos->verifcar_telefono_id();
        if($verificar != false){

            $comparacion = $extraer_datos->comparar_palabra_clave();
            
            if($comparacion == true) {
                //echo "holaaa";
                $extraer_datos->extraer_mensaje_principal();
            } else {
                $extraer_datos->buscar_ultimo_registro();
            }
                echo $extraer_datos->mensaje_recibido;
                $resultado = $extraer_datos->devolver_mensaje();
                var_dump($resultado);
                $enviar = new envio_mensaje($resultado, $data->telefono_cliente, $extraer_datos->telefono_id, $extraer_datos->token);
                $enviar->enviar_mensaje();

                $extraer_datos->mensaje_enviado = $resultado->mensaje[0]['id_msg'];
                $extraer_datos->verificar_telefono_cliente();
                $extraer_datos->mensaje_recibido = $data->mensaje;
                $extraer_datos->guardar_interacion();
        }
    }

}