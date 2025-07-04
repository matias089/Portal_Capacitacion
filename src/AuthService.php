<?php
namespace PortalCapacitacion;

use PDO;

class AuthService
{
    private $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function validateRut(string $rut): array
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

        if (preg_match('/[^0-9kK\-]/', $rut)) {
            $response['message'] = 'El RUT contiene caracteres no válidos';
            return $response;
        }

        $parts = explode('-', $rut);
        
        // Check for lowercase k before digit validation
        if ($parts[1] === 'k') {
            $response['message'] = 'El dígito verificador solo puede ser la letara K o numerico';
            return $response;
        }

        if (!$this->validateRutParts($parts)) {
            $response['message'] = 'El RUT debe tener el formato correcto (ej: 12345678-9)';
            return $response;
        }

        if (!$this->validateVerifierDigit($parts[0], strtoupper($parts[1]))) {
            $response['message'] = 'El dígito verificador del RUT es incorrecto';
            return $response;
        }

        $response['isValid'] = true;
        return $response;
    }

    private function validateRutParts(array $parts): bool 
    {
        if (count($parts) !== 2) {
            return false;
        }
        $number = $parts[0];
        return strlen($number) >= 7 && strlen($number) <= 8 && preg_match('/^[0-9]+$/', $number);
    }

    private function validateVerifierDigit(string $number, string $verifier): bool 
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

    public function authenticate($rut, $password)
    {
        $validationResult = $this->validateRut($rut);
        if (!$validationResult['isValid']) {
            return ['error' => $validationResult['message']];
        }
        
        $passwordValidationResult = $this->validatePassword($password);
        if ($passwordValidationResult !== true) {
            return ['error' => $passwordValidationResult];
        }
        
        $sql = "SELECT rut, nombre, tipo_usuario, empresa FROM usuarios WHERE rut = :rut AND contrasena = :password";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['rut'] = $user['rut'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['tipo_usuario'] = $user['tipo_usuario'];
            $_SESSION['empresa'] = $user['empresa'];
            return $user;
        }
        return false;
    }
}


