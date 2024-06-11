<?php

// error_control.php
function customError($errno, $errstr, $errfile, $errline) {
    // Redirige a la vista de error
    header("Location: /Portal_Capacitacion/error.php");
    exit();
}

function customException($exception) {
    // Redirige a la vista de error
    header("Location: /Portal_Capacitacion/error.php");
    exit();
}

// Establece el manejador de errores y excepciones
set_error_handler("customError");
set_exception_handler("customException");

?>