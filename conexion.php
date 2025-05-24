<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/db/db.php';

use PortalCapacitacion\AuthService;
use PortalCapacitacion\LoginController;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rut']) && isset($_POST['password'])) {
    try {
        // Crear conexión PDO real
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $authService = new AuthService($pdo);
        $loginController = new LoginController($authService);
        
        $loginController->handleLogin($_POST['rut'], $_POST['password']);
    } catch (Exception $e) {
        error_log("Error de conexión: " . $e->getMessage());
        header("Location: /Portal_Capacitacion/login.php?error=2");
        exit();
    }
}
?>