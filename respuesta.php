<?php
//enviar.php
/*
 * RECIBIMOS LA RESPUESTA
*/
function enviar($recibido, $enviado, $idWA,$timestamp,$telefonoCliente) {
    require_once './conexion.php';
    //CONSULTAMOS TODOS LOS REGISTROS CON EL ID DEL MANSAJE
    $sqlCantidad = "SELECT count(id) AS cantidad FROM registro WHERE id_wa='" . $idWA . "';";
    $resultCantidad = $conn->query($sqlCantidad);
    //OBTENEMOS LA CANTIDAD DE MENSAJES ENCONTRADOS (SI ES 0 LO REGISTRAMOS SI NO NO)
    $cantidad = 0;
    //SI LA CONSULTA ARROJA RESULTADOS
    if ($resultCantidad) {
        //OBTENEMOS EL PRIMER REGISTRO
        $rowCantidad = $resultCantidad->fetch_row();
        //OBTENEMOS LA CANTIDAD DE REGISTROS
        $cantidad = $rowCantidad[0];
    }
    //SI LA CANTIDAD DE REGISTROS ES 0 ENVIAMOS EL MENSAJE DE LO CONTRARIO NO LO ENVIAMOS PORQUE YA SE ENVIO
    if ($cantidad == 0) {
        //TOKEN QUE NOS DA FACEBOOK
        $token = 'EAACrdMNCuUIBAKhztB5cRDPBOVgNRzZBjbdZBchgiuMNbUIZAPUzrITgw6jeKdsBZBD7Uii7ZBkr7lrugqbLQYXIZAI7ZAZC9hMjuZCV0ogX3QZAnCqobyRnVn4Oblc1LOozDhGcHrZCgFrvbMqm2SVZAOtNiFB7p1qF2JebQ1sllGvOlfZAvWx1mVp48So0D8NX4gYbRIz63ytcBiAZDZD';
        //NUESTRO TELEFONO
        $telefono = '543515301114';
        //IDENTIFICADOR DE NÚMERO DE TELÉFONO
        $telefonoID = '112035591929858';
        //URL A DONDE SE MANDARA EL MENSAJE
        $url = 'https://graph.facebook.com/v17.0/' . $telefonoID . '/messages';
        //CONFIGURACION DEL MENSAJE
        $mensaje = '{
            "recipient_type": "individual",
            "messaging_product": "whatsapp",
            "to": "'.$telefonoCliente.'",
            "type": "interactive",
            "interactive": {
                "type": "button",
                "header": {
                    "type": "image",
                        "image":{
                        "link": "https://institutotesla.com.ar/wp-content/uploads/2021/01/img_9514-reducida-1024x768.jpg"
                        }
                },
                "body": {
                    "text": "'.$enviado.'"
                },
                "footer": {
                    "text": "Derechos reserbados Instituto Tesla"
                  },
                "action": {
                    "buttons": [
                        {
                            "type": "reply",
                            "reply": {
                                "id": "1",
                                "title": "Obvio"
                            }
                        },
                        {
                            "type": "reply",
                            "reply": {
                                "id": "2",
                                "title": "No lo creo"
                            }
                        },
                        {
                            "type": "reply",
                            "reply": {
                                "id": "3",
                                "title": "Lo pensare"
                            }
                        }
                    ]
                }
            }
        }';
        //DECLARAMOS LAS CABECERAS
        $header = array("Authorization: Bearer " . $token, "Content-Type: application/json",);
        //INICIAMOS EL CURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //OBTENEMOS LA RESPUESTA DEL ENVIO DE INFORMACION
        $response = json_decode(curl_exec($curl), true);
        //OBTENEMOS EL CODIGO DE LA RESPUESTA
        file_put_contents("logs.json", json_encode($response), true);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        file_put_contents("logs.txt",$status_code);
        //CERRAMOS EL CURL
        curl_close($curl);

//localhost
        //INSERTAMOS LOS REGISTROS DEL ENVIO DEL WHATSAPP
        $sql = "INSERT INTO registro "
            . "(mensaje_recibido    ,mensaje_enviado   ,id_wa        ,timestamp_wa        ,     telefono_wa) VALUES "
            . "('" . $recibido . "' ,'" . $enviado . "','" . $idWA . "','" . $timestamp . "','" . $telefonoCliente . "');";
        $conn->query($sql);
        $conn->close();
    }
}