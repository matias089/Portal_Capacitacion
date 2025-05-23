<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class AuthService {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function validarRut($rut) {
        return preg_match('/^\d{7,8}-[0-9kK]{1}$/', $rut);
    }
    
    public function validarPassword($password) {
        return strlen($password) >= 8 &&
               preg_match('/[A-Z]/', $password) &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[0-9]/', $password) &&
               preg_match('/[\W]/', $password);
    }
    
    public function autenticarUsuario($rut, $password) {
        // Validaciones básicas
        if (empty($rut) || empty($password)) {
            return ['error' => "Los campos no pueden estar vacíos."];
        }
        
        if (!$this->validarRut($rut)) {
            return ['error' => "Formato de RUT inválido. Ej: 12345678-9"];
        }
        
        if (!$this->validarPassword($password)) {
            return ['error' => "Contraseña inválida. Debe tener al menos 8 caracteres, 1 mayúscula, 1 número y 1 símbolo."];
        }
        
        // Verificar usuario en BD
        $query = "SELECT rut, nombre, tipo_usuario, empresa FROM usuarios WHERE rut=$1 AND contrasena=$2";
        $result = pg_query_params($this->conn, $query, array($rut, $password));
        
        if (pg_num_rows($result) > 0) {
            $usuario = pg_fetch_assoc($result);
            
            // Actualizar última conexión
            $update_query = "UPDATE usuarios SET ultima_conexion = CURRENT_TIMESTAMP WHERE rut = $1";
            pg_query_params($this->conn, $update_query, array($rut));
            
            return [
                'success' => true,
                'usuario' => $usuario
            ];
        } else {
            return ['error' => "Usuario o contraseña incorrectos."];
        }
    }
}

// DATOS CONEXIÓN
include('db/db.php');
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    $error_message = "Error al conectar a la base de datos.";
    header('Location: login.php?error=' . urlencode($error_message));
    exit;
}

// OBTENER DATOS POST
$rut = $_POST['rut'] ?? '';
$password = $_POST['password'] ?? '';

// Crear instancia del servicio de autenticación
$authService = new AuthService($conn);
$resultado = $authService->autenticarUsuario($rut, $password);

if (isset($resultado['error'])) {
    header('Location: login.php?error=' . urlencode($resultado['error']));
    exit;
} else {
    $_SESSION['tipo_usuario'] = $resultado['usuario']['tipo_usuario'];
    $_SESSION['empresa'] = $resultado['usuario']['empresa'];
    $_SESSION['rut'] = $resultado['usuario']['rut'];
    $_SESSION['nombre'] = $resultado['usuario']['nombre'];
    
    header('Location: index.php');
}

pg_close($conn);
?>