<?php
// Iniciar sesión si no está iniciada
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['tipo_usuario'])) {
    // Si el usuario no está logueado, redirigir a la página de inicio de sesión
    header("Location: portada.html");
    exit(); // Es importante salir del script después de redirigir
}

// Incluye el contenido del navbar y la conexión a la base de datos
include 'navbar.php';
include 'db/db.php';

// Conexión a la base de datos
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Obtener el tipo de usuario de la sesión
$tipo_usuario = $_SESSION['tipo_usuario'];

// Consulta SQL para obtener las notificaciones correspondientes al tipo de usuario
$sql = "SELECT * FROM public.notificaciones WHERE tipo_usuario = $1 ORDER BY fecha DESC";
$result = pg_query_params($db, $sql, array($tipo_usuario));

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
    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/crear_usuario.css">
    <style>
        .container-div1 {
            width: 60vw;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="form1">
    <div class="inputBox1" style="text-align: left; margin-bottom:30px;">
        <a href="/Portal_Capacitacion/index.php"> <input type="submit" value="Volver"/> </a>
    </div>
</div>

<div class="container-div1">
    <h2>Notificaciones</h2>
    <?php
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
