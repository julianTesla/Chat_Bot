<?php 

include "./movimientos_datos.php";

$datos = new stdClass();
$datos->id = 72;
$datos->nombre = "hola";
$datos->encabezado = "hola";
$datos->cuerpo = "hola";
$datos->pie = "hola";
$datos->tipo = 1;
$datos->estado = 0;

$acer = new main($datos);

$data = $acer->comprobar_relacion();

$unidimensionalArray = array_map(function($subarray) {
    return $subarray[0];
}, $data);

// Imprimir el nuevo array unidimensional

//var_dump($unidimensionalArray);

$array1 = array('74', 'fkpewf', '86', '45665',12345);
$iguales = array_diff($unidimensionalArray, $array1);

$datos = $acer->eliminar_opciones(118);
echo $datos;



