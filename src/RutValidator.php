<?php
namespace PortalCapacitacion\Validators;

class RutValidator 
{
    private const REGEX_FORMAT = '/^[0-9]{7,8}-[0-9kK]$/';
    private const REGEX_ONLY_NUMBERS = '/^[0-9]+$/';
    private const REGEX_SPECIAL_CHARS = '/[^0-9kK\-]/';

    public static function validateFormat(string $rut): bool 
    {
        if (empty($rut)) {
            return false;
        }
        return (bool)preg_match(self::REGEX_FORMAT, $rut);
    }

    public static function validateRut(string $rut): array 
    {
        $response = ['isValid' => false, 'message' => ''];

        if (empty($rut)) {
            $response['message'] = 'El RUT no puede estar vacío';
            return $response;
        }

        if (strpos($rut, '.') !== false) {
            $response['message'] = 'El RUT no debe contener puntos';
            return $response;
        }

        if (strpos($rut, '-') === false) {
            $response['message'] = 'El RUT debe contener un guión antes del dígito verificador';
            return $response;
        }

        if (preg_match(self::REGEX_SPECIAL_CHARS, $rut)) {
            $response['message'] = 'El RUT contiene caracteres no válidos';
            return $response;
        }

        $parts = explode('-', $rut);
        if (!self::validateRutParts($parts)) {
            $response['message'] = 'El RUT debe tener el formato correcto (ej: 12345678-9)';
            return $response;
        }

        if (!self::validateVerifierDigit($parts[0], strtoupper($parts[1]))) {
            $response['message'] = 'El dígito verificador del RUT es incorrecto';
            return $response;
        }

        $response['isValid'] = true;
        return $response;
    }

    private static function validateRutParts(array $parts): bool 
    {
        if (count($parts) !== 2) {
            return false;
        }

        $number = $parts[0];
        return strlen($number) >= 7 && strlen($number) <= 8 && preg_match(self::REGEX_ONLY_NUMBERS, $number);
    }

    private static function validateVerifierDigit(string $number, string $verifier): bool 
    {
        $sum = 0;
        $multiplier = 2;

        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $sum += intval($number[$i]) * $multiplier;
            $multiplier = $multiplier === 7 ? 2 : $multiplier + 1;
        }

        $expectedVerifier = 11 - ($sum % 11);
        
        if ($expectedVerifier === 11) {
            return $verifier === '0';
        }
        if ($expectedVerifier === 10) {
            return $verifier === 'K';
        }
        return $verifier === (string)$expectedVerifier;
    }
}

use PortalCapacitacion\Validators\RutValidator;

$result = RutValidator::validateRut('12345678-9');
if ($result['isValid']) {
    // RUT is valid
} else {
    echo $result['message']; // Shows validation error message
}
