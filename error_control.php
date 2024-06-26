<?php
if (!function_exists('customError')) {
    function customError($errno, $errstr, $errfile, $errline) {
        header("Location: /Portal_Capacitacion/error.php");
        exit();
    }
}

if (!function_exists('customException')) {
    function customException($exception) {
        header("Location: /Portal_Capacitacion/error.php");
        exit();
    }
}

set_error_handler("customError");
set_exception_handler("customException");
?>