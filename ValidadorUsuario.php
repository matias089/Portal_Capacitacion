<?php

class ValidadorUsuario {

    // Valida formato de RUT: solo números + guion + dígito verificador
    public static function validarRut($rut) {
        if (empty($rut)) return false;
        if (!preg_match('/^\d{7,8}-[\dkK]$/', $rut)) return false;

        // Verificación del dígito (opcional pero recomendado)
        list($numero, $dv) = explode('-', $rut);
        return strtolower($dv) === strtolower(self::calcularDigitoVerificador($numero));
    }

    // Algoritmo para calcular dígito verificador del RUT chileno
    private static function calcularDigitoVerificador($rut) {
        $s = 1;
        $m = 0;
        for (; $rut != 0; $rut = (int)($rut / 10)) {
            $s = ($s + $rut % 10 * (9 - $m++ % 6)) % 11;
        }
        return $s ? (string)($s - 1) : 'k';
    }

    // Valida contraseña segura (mínimo 8 caracteres, al menos una mayúscula, un número y un carácter especial)
    public static function validarContrasena($contrasena) {
        if (empty($contrasena)) return false;

        $longitudValida = strlen($contrasena) >= 8;
        $tieneMayuscula = preg_match('/[A-Z]/', $contrasena);
        $tieneNumero = preg_match('/\d/', $contrasena);
        $tieneEspecial = preg_match('/[^a-zA-Z\d]/', $contrasena);

        return $longitudValida && $tieneMayuscula && $tieneNumero && $tieneEspecial;
    }
}
