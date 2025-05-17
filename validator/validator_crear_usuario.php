<?php

class Validator
{
    public static function validarRut($rut)
    {
        // Formato válido: sin punto, con guion
        return preg_match('/^[0-9]{7,8}-[0-9kK]$/', $rut);
    }

    public static function validarContrasena($contrasena)
    {
        $longitud = strlen($contrasena) >= 8;
        $mayuscula = preg_match('/[A-Z]/', $contrasena);
        $numero = preg_match('/[0-9]/', $contrasena);
        $especial = preg_match('/[\W_]/', $contrasena); // símbolo especial o guion bajo
        $espacio = !preg_match('/\s/', $contrasena); // sin espacios

        return $longitud && $mayuscula && $numero && $especial && $espacio;
    }

    public static function validarNombre($nombre)
    {
        return !empty(trim($nombre));
    }

    public static function validarCorreo($correo)
    {
        return filter_var($correo, FILTER_VALIDATE_EMAIL);
    }

    public static function validarEmpresa($empresa)
    {
        // Supongamos que "Nevada" es válida, vacío no
        return $empresa === "Nevada";
    }

    public static function validarTipoUsuario($tipo)
    {
        // Solo se acepta "Administrador" como válido
        return $tipo === "Administrador";
    }

    public static function validarCargo($cargo)
    {
        return !empty(trim($cargo));
    }
}
