<?php
// Iniciar sesión si no está iniciada
session_start();
include 'error_control.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['tipo_usuario'])) {
    // Si el usuario no está logueado, redirigir a la página de inicio de sesión
    header("Location: portada.html");
    exit(); // Es importante salir del script después de redirigir
}

// Incluye el contenido del navbar y la conexión a la base de datos
include 'navbar.php';
include 'db/db.php';

// Configurar la zona horaria a la de Chile
date_default_timezone_set('America/Santiago');

// Conexión a la base de datos
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Obtener el tipo de usuario de la sesión
$tipo_usuario = $_SESSION['tipo_usuario'];

// Inicializar el mensaje de notificación
$mensaje_html = "";

// Si el usuario es administrador y se envía el formulario, procesar la creación de la notificación
if ($_SERVER["REQUEST_METHOD"] == "POST" && $tipo_usuario == 'Administrador') {
    $titulo = $_POST['titulo'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';
    $tipo_usuario_destino = $_POST['tipo_usuario'] ?? '';
    $fecha = date("Y-m-d H:i");

    // Convertir el tipo de usuario a la forma esperada en la base de datos
    $tipo_usuario_destino = ucfirst(strtolower($tipo_usuario_destino));

    // Verificar si los campos están completos
    if ($titulo && $mensaje && $tipo_usuario_destino) {
        // Preparar la consulta SQL para insertar la nueva notificación
        $query = "INSERT INTO public.notificaciones (titulo, mensaje, tipo_usuario, fecha) VALUES ($1, $2, $3, $4)";
        $result = pg_query_params($db, $query, array($titulo, $mensaje, $tipo_usuario_destino, $fecha));

        if ($result) {
            $mensaje_html = '<div class="alert alert-success" role="alert">Notificación creada correctamente.</div>';
        } else {
            $mensaje_html = '<div class="alert alert-danger" role="alert">Error al crear la notificación: ' . pg_last_error($db) . '</div>';
        }
    } else {
        $mensaje_html = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios.</div>';
    }
}

// Consulta SQL para obtener todas las notificaciones (sin filtro por tipo de usuario)
$sql = "SELECT * FROM public.notificaciones ORDER BY fecha DESC";
$result = pg_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>Notificaciones</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/notificaciones.css">
    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/crear_usuario.css">
</head>
<body>

<div class="form1">
    <div class="inputBox1" style="text-align: left; margin-bottom:30px;">
        <a href="/Portal_Capacitacion/index.php"> <input type="submit" value="Volver"/> </a>
    </div>
</div>

<div class="container-div1">
    <h2>Notificaciones</h2>
    <?php echo $mensaje_html; ?>

    <?php
    // Verificar si el usuario es administrador y mostrar el formulario de creación de notificaciones
    if ($tipo_usuario == 'Administrador') {
        echo '
        <div class="admin-form">
            <h3>Crear Notificación</h3>
            <form action="notificaciones.php" method="post">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>
                <div class="mb-3">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea class="form-control" id="mensaje" name="mensaje" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="tipo_usuario" class="form-label">Tipo de Usuario</label>
                    <select class="form-control" id="tipo_usuario" name="tipo_usuario" required>
                        <option value="Administrador">Administrador</option>
                        <option value="Usuario">Usuario</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Crear Notificación</button>
            </form>
        </div>
        ';
    }

    // Verificar si se encontraron notificaciones
    if (pg_num_rows($result) > 0) {
        // Crear una tabla para mostrar las notificaciones
        echo "<table>";
        echo "<tr><th>Título</th><th>Mensaje</th><th>Fecha</th></tr>";

        // Mostrar cada notificación en una fila de la tabla
        while ($row = pg_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['mensaje']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron notificaciones.";
    }
    ?>
</div>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
pg_close($db);
?>
