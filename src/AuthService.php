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
    
    public function authenticate($rut, $password)
    {
        // Primero validar el formato del RUT
        $rutValidationResult = $this->validateRut($rut);
        if ($rutValidationResult !== true) {
            return ['error' => $rutValidationResult];
        }
        
        // Luego validar el formato de la contraseña
        $passwordValidationResult = $this->validatePassword($password);
        if ($passwordValidationResult !== true) {
            return ['error' => $passwordValidationResult];
        }
        
        // Si ambas validaciones pasan, proceder con la consulta a la BD
        $sql = "SELECT rut, nombre, tipo_usuario, empresa FROM usuarios WHERE rut = :rut AND contrasena = :password";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Iniciar sesión
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
    
    /**
     * Valida el formato del RUT chileno
     * @param string $rut
     * @return true|string true si es válido, mensaje de error si no
     */
    public function validateRut($rut)
    {
        // Verificar que no esté vacío
        if (empty($rut) || $rut === null) {
            return 'El RUT no puede estar vacío';
        }
        
        // AC-TC-8: Verificar que no contenga puntos (validación específica primero)
        if (strpos($rut, '.') !== false) {
            return 'El RUT no debe contener puntos';
        }
        
        // AC-TC-7: Verificar que contenga guión
        if (strpos($rut, '-') === false) {
            return 'El RUT debe contener un guión antes del dígito verificador';
        }
        
        // AC-TC-10: Verificar caracteres especiales o símbolos (excepto guión y números y K)
        if (preg_match('/[^0-9kK\-]/', $rut)) {
            return 'El RUT contiene caracteres no válidos';
        }
        
        // Verificar formato básico: números-dv
        if (!preg_match('/^[0-9]{7,8}-[0-9kK]$/', $rut)) {
            return 'El RUT debe tener el formato correcto (ej: 12345678-9)';
        }
        
        // AC-TC-9: Validar dígito verificador
        $parts = explode('-', $rut);
        if (count($parts) !== 2) {
            return 'El RUT debe tener exactamente un guión';
        }
        
        $numero = $parts[0];
        $dv = strtoupper($parts[1]);
        
        // Verificar que el número tenga entre 7 y 8 dígitos
        if (strlen($numero) < 7 || strlen($numero) > 8) {
            return 'El número del RUT debe tener entre 7 y 8 dígitos';
        }
        
        // Calcular dígito verificador correcto
        $dvCalculado = $this->calcularDigitoVerificador($numero);
        
        if ($dv !== $dvCalculado) {
            return 'El dígito verificador del RUT es incorrecto';
        }
        
        return true;
    }
    
    /**
     * Calcula el dígito verificador de un RUT
     * @param string $numero
     * @return string
     */
    private function calcularDigitoVerificador($numero)
    {
        $suma = 0;
        $multiplicador = 2;
        
        // Recorrer el número de derecha a izquierda
        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += intval($numero[$i]) * $multiplicador;
            $multiplicador++;
            if ($multiplicador > 7) {
                $multiplicador = 2;
            }
        }
        
        $resto = $suma % 11;
        $dv = 11 - $resto;
        
        if ($dv === 11) {
            return '0';
        } elseif ($dv === 10) {
            return 'K';
        } else {
            return strval($dv);
        }
    }
    
    /**
     * Valida el formato de la contraseña según los requisitos del sistema
     * @param string $password
     * @return true|string true si es válida, mensaje de error si no
     */
    public function validatePassword($password)
    {
        // AC-TC-22: Contraseña vacía o nula
        if (empty($password) || $password === null) {
            return 'La contraseña no puede estar vacía';
        }
        
        // AC-TC-18: Menos de 8 caracteres
        if (strlen($password) < 8) {
            return 'La contraseña debe tener al menos 8 caracteres';
        }
        
        // AC-TC-19: Sin mayúscula
        if (!preg_match('/[A-Z]/', $password)) {
            return 'La contraseña debe contener al menos una letra mayúscula';
        }
        
        // AC-TC-21: Sin número
        if (!preg_match('/[0-9]/', $password)) {
            return 'La contraseña debe contener al menos un número';
        }
        
        // AC-TC-20: Sin carácter especial
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return 'La contraseña debe contener al menos un carácter especial';
        }
        
        return true;
    }
    
    public function getRedirectUrl($tipoUsuario)
    {
        return '/Portal_Capacitacion/index.php';
    }
}
