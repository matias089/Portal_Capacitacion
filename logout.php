<?php

// Incluye el archivo de conexión para tener acceso a la sesión
// include('/var/www/html/Portal_Capacitacion/db/db.php');
include 'db/db.php';

// Elimina todas las variables de sesión
$_SESSION = array();

// Borra la cookie de la sesión, si existe
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 86400, '/');
}

// Finaliza la sesión
session_destroy();

// Redirige al usuario a la página de inicio de sesión
header("Location: login.php");
exit;
?>
