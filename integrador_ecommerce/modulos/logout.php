<?php
// Iniciar la sesión
session_start();

// Vaciar todas las variables de sesión
$_SESSION = array();

// Destruir la sesión
session_destroy();

// Redireccionar al usuario al formulario de inicio de sesión
header("Location: inicio.php");
exit();
?>
