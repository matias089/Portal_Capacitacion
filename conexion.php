<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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

// FUNCIONES DE VALIDACIÓN (Clases de equivalencia)
function validarRut($rut) {
    return preg_match('/^\d{7,8}-[0-9kK]{1}$/', $rut);
}

function validarPassword($password) {
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/[0-9]/', $password) &&
           preg_match('/[\W]/', $password); // símbolo especial
}

// VALIDAR ENTRADAS
if (empty($rut) || empty($password)) {
    $error_message = "Los campos no pueden estar vacíos.";
    header('Location: login.php?error=' . urlencode($error_message));
    exit;
}

if (!validarRut($rut)) {
    $error_message = "Formato de RUT inválido. Ej: 12345678-9";
    header('Location: login.php?error=' . urlencode($error_message));
    exit;
}

if (!validarPassword($password)) {
    $error_message = "Contraseña inválida. Debe tener al menos 8 caracteres, 1 mayúscula, 1 número y 1 símbolo.";
    header('Location: login.php?error=' . urlencode($error_message));
    exit;
}

// VERIFICAR USUARIO EN BD
$query = "SELECT rut, nombre, tipo_usuario, empresa FROM usuarios WHERE rut=$1 AND contrasena=$2";
$result = pg_query_params($conn, $query, array($rut, $password));

if (pg_num_rows($result) > 0) {
    $usuario = pg_fetch_assoc($result);

    // Actualizar última conexión
    $update_query = "UPDATE usuarios SET ultima_conexion = CURRENT_TIMESTAMP WHERE rut = $1";
    pg_query_params($conn, $update_query, array($rut));

    $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
    $_SESSION['empresa'] = $usuario['empresa'];
    $_SESSION['rut'] = $usuario['rut'];
    $_SESSION['nombre'] = $usuario['nombre'];

    header('Location: index.php');
} else {
    $error_message = "Usuario o contraseña incorrectos.";
    header('Location: login.php?error=' . urlencode($error_message));
}

pg_close($conn);
?>
