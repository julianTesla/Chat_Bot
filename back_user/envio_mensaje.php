<?php

class envio_mensaje {

    private $encabezado;
    private $cuerpo;
    private $pie;
    private $tipo;
    private $telefono_cliente;
    private $opciones;
    private $token;
    private $telefono_id;
    private $url = 'https://graph.facebook.com/v17.0/';

    public function __construct($data, $telefono_cliente, $telefono_id, $token_api) {
        $this->encabezado = $data->mensaje[0]['encabezado_msg'];
        $this->cuerpo = $data->mensaje[0]['cuerpo_msg'];
        $this->pie = $data->mensaje[0]['pie_msg'];
        $this->telefono_cliente = $telefono_cliente;
        $this->tipo = $data->mensaje[0]['tipo_msg'];
        $this->opciones = $data->opciones;
        $this->telefono_id = $telefono_id;
        $this->token = $token_api;
    }

    public function enviar_mensaje() {

        switch ($this->tipo) {
            case 1:
                $this->enviar_mensaje_texto();
                break;
            case 2:
                $this->enviar_mensaje_boton();
                break;
            case 3:
                $this->enviar_mensaje_lista();
                break;
        }
    }

    private function enviar_mensaje_lista() {
        $mensaje='';
        $mensaje = '{
            "recipient_type": "individual",
            "messaging_product": "whatsapp",
            "to": "' . $this->telefono_cliente . '",
            "type": "interactive",
            "interactive": {
            "type": "list",';
            if($this->encabezado != "") {
                $mensaje .= '"header": {
                                "type": "text",
                                "text": '.json_encode($this->encabezado).'
                            },';
            }
    
            if($this->cuerpo != "") {
                $mensaje .= '"body": {
                                "text": '.json_encode($this->cuerpo).'
                            },';
            }
            if($this->pie != "") {
                $mensaje .= '"footer": {
                                "text": '.json_encode($this->pie).'
                            },';
            }
            if(count($this->opciones) > 0) {

                $mensaje .= ' "action": {
                                "button": "Abrir menu",
                                "sections":[';
    
                foreach ($this->opciones as $valor) {
                    $mensaje .= '{
                                    "title":"selecciona una opcion",
                                    "rows": [
                                        {
                                            "id":"'.$valor['opciones_id'].'",
                                            "title": "'.$valor['nombre_msg'].'", 
                                        }
                                    ]
                                },';
                }
    
                $mensaje.=']
                                    }
                                }
                            }';
            $this->enviar_api_wsp($mensaje);
            } else {
                $this->enviar_mensaje_texto();
            }
            
    }

    public function enviar_mensaje_boton() {
        $mensaje='';
        $mensaje = '{
            "recipient_type": "individual",
            "messaging_product": "whatsapp",
            "to": "'.$this->telefono_cliente.'",
            "type": "interactive",
            "interactive": {
                "type": "button",';
        if($this->encabezado != "") {
            $mensaje .= '"header": {
                            "type": "text",
                            "text": '.json_encode($this->encabezado).'
                        },';
        }
        if($this->cuerpo != "") {
            $mensaje .= '"body": {
                            "text": '.json_encode($this->cuerpo).'
                        },';
        }
        if($this->pie != "") {
            $mensaje .= '"footer": {
                            "text": '.json_encode($this->pie).'
                        },';
        }
        if(count($this->opciones) > 0) {
            $mensaje .= '"action": {
                            "buttons": [';

            foreach ($this->opciones as $valor) {
                $mensaje .= '{
                                "type": "reply",
                                "reply": {
                                    "id": "'.$valor['opciones_id'].'",
                                    "title": "'.$valor['nombre_msg'].'"
                                }
                            },';
            }
            $mensaje.=']
                        }
                    }
                    }';
            $this->enviar_api_wsp($mensaje);
        } else {
            $this->enviar_mensaje_texto();
        }
    }


    private function enviar_mensaje_texto() {
        $mensaje='';
        $mensaje = '
                    {
                        "preview_url": true,
                        "messaging_product": "whatsapp", 
                        "recipient_type": "individual",
                        "to": "' . $this->telefono_cliente . '",
                        "type": "text", 
                            "text":
                            {
                                "body":' .json_encode($this->cuerpo).',
                                "preview_url": true,
                            }
                    }';
        $this->enviar_api_wsp($mensaje);
    }

    private function enviar_api_wsp($datos) {
        $url = $this->url.$this->telefono_id.'/messages';

        $json = json_encode($datos);
        file_put_contents("logs.TXT", $datos);
        $header = array("Authorization: Bearer " . $this->token, "Content-Type: application/json",);

        //INICIAMOS EL CURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $datos);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($curl), true);
        //OBTENEMOS EL CODIGO DE LA RESPUESTA
        file_put_contents("logs.json", json_encode($response), true);
        echo $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
    }
}