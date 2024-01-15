<?php
session_start();
unset($_SESSION["usuario"]);//vaciamos el parametro que contiene la session
session_destroy();    //y destruimos la session
echo '<script type="text/javascript">
            window.location="../index.php";
            </script>';
?>