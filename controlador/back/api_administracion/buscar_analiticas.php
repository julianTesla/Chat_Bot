<?php

require_once "../conex.php";
class analiticas {

    private $token;
    public $fecha_1;
    public $fecha_2;
    public $id_numero;
    public $granualidad;

    public function __construct ($datos) {

        $this->fecha_1 = $datos->fecha_1;
        $this->fecha_2 = $datos->fecha_2;
        $this->id_numero = $datos->id_numero;
        $this->granualidad = $datos->granualidad;
    }


    public function extraer_numeros () {
        $url = "https://graph.facebook.com/v17.0/3506551706286705/owned_whatsapp_business_accounts";
        $respuesta = $this->ejecutar_solicitud($url);
        return $respuesta;
    }


    public function extraer_analiticas () {
        if($this->granualidad == 1) {
            $modo_fecha = "DAY";
        }
        if($this->granualidad == 2) {
            $modo_fecha = "MONTH";
        }
        $this->convertir_fechas();

        $url = "https://graph.facebook.com/v17.0/".$this->id_numero."?fields=analytics.start(".$this->fecha_1.").end(".$this->fecha_2.").granularity(".$modo_fecha.").phone_numbers([])";
        $respuesta = $this->ejecutar_solicitud($url);
        file_put_contents("analiticas.json", $respuesta);
        $data = json_decode($respuesta, true);

        $datos = [];
        $aux = 0;
        if (isset($data['analytics']['data_points']) && is_array($data['analytics']['data_points'])) {

            foreach ($data['analytics']['data_points'] as $dataPoint) {
                $start = date("Y-m-d", $dataPoint['start']);
                $end = date("Y-m-d", $dataPoint['end']);
                $sent = $dataPoint['sent'];
                $delivered = $dataPoint['delivered'];
                $datos[$aux] = array(
                    "granularity" => $data['analytics']['granularity'],
                    "puntos_datos" => array(
                        "fecha_ini" => $start,
                        "fecha_fin" => $end,
                        "enviados" => $sent,
                        "entregados" => $delivered
                    )
                );
                $aux++;
            }
        } else {
            echo '{"Error":"No se encontraron puntos de datos"}';
        }
        return json_encode($datos);
    }


    public function extraer_coneversaciones () {
        
        if($this->granualidad == 1) {
            $modo_fecha = "DAILY";
        }
        if($this->granualidad == 2) {
            $modo_fecha = "MONTHLY";
        }
        $this->convertir_fechas();

        $url = 'https://graph.facebook.com/v17.0/'.$this->id_numero.'?fields=conversation_analytics.start('.$this->fecha_1.').end('.$this->fecha_2.').granularity('.$modo_fecha.').phone_numbers([]).dimensions(["CONVERSATION_CATEGORY","CONVERSATION_TYPE","COUNTRY","PHONE"])';
        $respuesta = $this->ejecutar_solicitud($url);
        file_put_contents("analiticas_conversation.json", $respuesta);
        $data = json_decode($respuesta, true);
        
        $datos = [];
        $aux = 0;
        if ($data['conversation_analytics']['data'][0]['data_points']) {
            $dataPoints = $data['conversation_analytics']['data'][0]['data_points'];
            
            foreach ($dataPoints as $dataPoint) {

                $start = $dataPoint['start'];
                $end = $dataPoint['end'];
                $conversation = $dataPoint['conversation'];
                $phoneNumber = $dataPoint['phone_number'];
                $country = $dataPoint['country'];
                $conversationType = $dataPoint['conversation_type'];
                $conversationCategory = $dataPoint['conversation_category'];
                $cost = $dataPoint['cost'];

                $datos[$aux] = array(
                    "granularity" => $this->granualidad,
                    "puntos_datos" => array(
                        "fecha_ini" => date("Y-m-d", $start),
                        "fecha_fin" => date("Y-m-d", $end),
                        "conversacion" => $conversation,
                        "numero_telefono" => $phoneNumber,
                        "pais" => $country,
                        "tipo_conversacion" => $conversationType,
                        "categoria_conversacion" => $conversationCategory,
                        "costo" => $cost
                    )
                );
                $aux++;
            }
        
            return json_encode($datos);
        } else {
            echo '{"Error":"No se encontraron puntos de datos"}';
        }
    }

    private function ejecutar_solicitud ($url) {
       $token_provisorio = 'EAACrdMNCuUIBO866VRUUtf1UTh4GB7RohhZA1iwbIYbrATjtZAZBd7cIrh14gf8k1xZASV8iElxUjKLMiv7Mm5RGECCxpMUKoLgjqwfS5SprY5ZAsJpQAppZBlYrQ9L3ZCSQX1LqKPD2eVI0AYZA7qYa4gMCpT4qTf9KqHFlgZAtgEI4TQeSv8iLlZCM8SCbJCPWWugsWn0Y7WF2Iu';
       // $this->extraer_token();

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token_provisorio,
        ));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error al realizar la solicitud cURL: ' . curl_error($ch);
        }

        curl_close($ch);
        return $response;
    }

    public function extraer_token () {
        $conn = new Conexion();
        $sql= "SELECT token_api FROM numeros WHERE telefono_id = :ID_NUMERO AND estado = 0";
        $consulta = $conn->prepare($sql);
        $consulta -> bindParam(":ID_NUMERO", $this->id_numero);
        $consulta -> execute();
        $respuesta = $consulta -> fetchAll(PDO::FETCH_ASSOC);
        $this->token = $respuesta['token_api'];
    }

    public function convertir_fechas () {
        $this->fecha_1 = strtotime($this->fecha_1);
        $this->fecha_2 = strtotime($this->fecha_2);     
    }
}