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
        $token = 'EAACrdMNCuUIBO2fpv6KU8ZBewNuSa2T6s9Er1FM3bZC9nrcCQZCVa85E62Q3doGiuXiiIYJZCEYGYNAQNdPk6N6SzqZAzqvyMdMqQqbkAHHgF0EgUgN4KZBNbwo7e1twkNTqZC0n8e3gJQxK2yTKs2NJKMmgXZCZC75x8HvaSgJZAdOzig6OT3JksH2ZCJzzZCGYQiCJCXN7M32rn2y9fYYZD';
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
            "to": "5493515301114",
            "type": "interactive",
            "interactive": {
                "type": "button","header": {
                            "type": "text",
                            "text": "hola encabezado de mensage"
                        },"body": {
                            "text": "123456789"
                        },"footer": {
                            "text": "pie del mensage"
                        },"action": {
                            "buttons": [{
                                "type": "reply",
                                "reply": {
                                    "id": "183",
                                    "title": "locodoewqkdioewfioew"
                                }
                            },{
                                "type": "reply",
                                "reply": {
                                    "id": "184",
                                    "title": "2"
                                }
                            },{
                                "type": "reply",
                                "reply": {
                                    "id": "185",
                                    "title": "3"
                                }
                            },]
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
        
        //CERRAMOS EL CURL
        curl_close($curl);

        //INSERTAMOS LOS REGISTROS DEL ENVIO DEL WHATSAPP
        $sql = "INSERT INTO registro "
            . "(mensaje_recibido    ,mensaje_enviado   ,id_wa        ,timestamp_wa        ,     telefono_wa) VALUES "
            . "('" . $recibido . "' ,'" . $enviado . "','" . $idWA . "','" . $timestamp . "','" . $telefonoCliente . "');";
        $conn->query($sql);
        $conn->close();
    }
}
