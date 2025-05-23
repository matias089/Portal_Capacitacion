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
            
            return true;
        }
        
        return false;
    }
}