<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/db/db.php';

use PortalCapacitacion\AuthService;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rut']) && isset($_POST['password'])) {
    try {
        // Crear conexión PDO real
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $authService = new AuthService($pdo);
        
        if ($authService->authenticate($_POST['rut'], $_POST['password'])) {
            // Redireccionar según tipo de usuario
            switch ($_SESSION['tipo_usuario']) {
                case 'admin':
                    header("Location: /admin_index.php");
                    break;
                case 'usuario':
                    header("Location: /user_index.php");
                    break;
                default:
                    header("Location: /index.php");
            }
            exit();
        } else {
            header("Location: /login.php?error=1");
            exit();
        }
    } catch (Exception $e) {
        error_log("Error de autenticación: " . $e->getMessage());
        header("Location: /login.php?error=2");
        exit();
    }
}
?>