<?php

// Inicia la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('db/db.php');

// Conexión a la base de datos
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Verificar la conexión
if (!$conn) {
    echo "Error: No se pudo conectar a la base de datos.\n";
    exit;
}

// Obtener los datos del formulario
$rut = $_POST['rut'];
$password = $_POST['password'];

// Consulta para verificar las credenciales del usuario y obtener su nombre
$query = "SELECT rut, nombre, tipo_usuario, empresa FROM usuarios WHERE rut='$rut' AND contrasena='$password'";
$result = pg_query($conn, $query);

// Verificar si se encontraron resultados
if (pg_num_rows($result) > 0) {
    // Credenciales válidas, asignar el tipo de usuario
    $update_query = "UPDATE usuarios SET ultima_conexion = CURRENT_TIMESTAMP WHERE rut = '$rut'";
    pg_query($conn, $update_query);

    $usuario = pg_fetch_assoc($result);
    $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
    $_SESSION['empresa'] = $usuario['empresa'];
    $_SESSION['rut'] = $usuario['rut'];
    $_SESSION['nombre'] = $usuario['nombre']; // Se agrega el nombre del usuario a la sesión
    header('Location: index.php');
} else {
    // Credenciales inválidas, redirigir al usuario de vuelta al formulario de login
    // y mostrar un mensaje de error
    $error_message = "Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.";
    header('Location: login.php?error=' . urlencode($error_message));
}

// Cerrar la conexión a la base de datos
pg_close($conn);
?>
