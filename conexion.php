<?php
include 'error_control.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('db/db.php');

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo "Error: No se pudo conectar a la base de datos.\n";
    exit;
}

$rut = $_POST['rut'];
$password = $_POST['password'];

$query = "SELECT rut, nombre, tipo_usuario, empresa FROM usuarios WHERE rut='$rut' AND contrasena='$password'";
$result = pg_query($conn, $query);

if (pg_num_rows($result) > 0) {
    $update_query = "UPDATE usuarios SET ultima_conexion = CURRENT_TIMESTAMP WHERE rut = '$rut'";
    pg_query($conn, $update_query);

    $usuario = pg_fetch_assoc($result);
    $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
    $_SESSION['empresa'] = $usuario['empresa'];
    $_SESSION['rut'] = $usuario['rut'];
    $_SESSION['nombre'] = $usuario['nombre'];
    header('Location: index.php');
} else {
    $error_message = "Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.";
    header('Location: login.php?error=' . urlencode($error_message));
}
pg_close($conn);
?>
