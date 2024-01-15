<?php 
$cadena1 = "MEnu1";
$cadena2 = "MeNU1";

if (mb_strtolower($valor['nombre_msg'], 'UTF-8') == mb_strtolower($texto, 'UTF-8')) {
    echo "Las cadenas son iguales sin importar mayúsculas y acentos.";
} else {
    echo "Las cadenas son diferentes.";
}
