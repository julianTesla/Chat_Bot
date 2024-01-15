<?php

//DESHABILITAMOS EL MOSTRAR ERRORES
 ini_set('display_errors', 0);
 ini_set('display_startup_errors', 0);
 error_reporting(-1);


$usuario=$_POST['usuario'];
$contraseña=$_POST['clave'];

// $cookie_name = "user";
// $cookie_value = "John Doe";

if($usuario == 'administrador' and $contraseña == 'Tesla.01*'){
  session_start();
  //setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
  $_SESSION["usuario"] = $usuario;
echo '<script type="text/javascript">
window.location="../Admin/numeros.php";
</script>';

} else {
  $error = "incorrecto";
  echo '<script type="text/javascript">
window.location="../index.php?error='.$error.'";
</script>';
}

?>
