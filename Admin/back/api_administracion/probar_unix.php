<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
// Un ejemplo de timestamp Unix
$timestampUnix = 1687575600; // Este valor es solo un ejemplo, reemplázalo con el que tengas

// Convierte el timestamp Unix a una representación de fecha y hora legible
$fechaHoraLegible = date("Y-m-d H:i:s", $timestampUnix);

echo "Fecha y Hora Legible: " . $fechaHoraLegible ."    ";

echo time();

$ch = curl_init();
$url = "https://graph.facebook.com/LATEST-VERSION/WHATSAPP-BUSINESS-ACCOUNT-ID?fields=analytics.start(1641024000).end(1651094880).granularity(DAY)&
access_token=USER-ACCESS-TOKEN";
?>