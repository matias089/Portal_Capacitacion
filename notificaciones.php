<?php
session_start();
include 'error_control.php';

if (!isset($_SESSION['tipo_usuario'])) {
    header("Location: portada.html");
    exit();
}

include 'navbar.php';
include 'db/db.php';

date_default_timezone_set('America/Santiago');

$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

$tipo_usuario = $_SESSION['tipo_usuario'];

$mensaje_html = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && $tipo_usuario == 'Administrador') {
    $titulo = $_POST['titulo'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';
    $tipo_usuario_destino = $_POST['tipo_usuario'] ?? '';
    $fecha = date("Y-m-d H:i");

    $tipo_usuario_destino = ucfirst(strtolower($tipo_usuario_destino));

    if ($titulo && $mensaje && $tipo_usuario_destino) {
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

    if (pg_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Título</th><th>Mensaje</th><th>Fecha</th></tr>";

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
pg_close($db);
?>
