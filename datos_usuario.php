<?php
// Iniciar sesión si no está iniciada
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['rut'])) {
    // Si el usuario no está logueado, redirigir a la página de inicio de sesión
    header("Location: portada.html");
    exit(); // Es importante salir del script después de redirigir
}

// Incluye el contenido del navbar y la conexión a la base de datos
include 'navbar.php';
include 'db/db.php';

// Conexión a la base de datos
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Obtener el rut del usuario iniciado de la sesión
$rut_usuario = $_SESSION['rut'];

// Consulta SQL para obtener los datos del usuario
$sql = "SELECT * FROM public.usuarios WHERE rut = $1";
$result = pg_query_params($db, $sql, array($rut_usuario));

// Verificar si se enviaron datos para actualizar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];

    // Actualizar los datos del usuario en la base de datos
    $update_sql = "UPDATE public.usuarios SET nombre = $1, correo = $2 WHERE rut = $3";
    $update_result = pg_query_params($db, $update_sql, array($nombre, $correo, $rut_usuario));

    if ($update_result) {
        echo "<div class='alert alert-success'>Datos actualizados correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar los datos.</div>";
    }

    // Recargar los datos actualizados
    $result = pg_query_params($db, $sql, array($rut_usuario));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>Datos Personales</title>
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
            display: flex;
            justify-content: space-between;
        }
        .user-data {
            width: 60%;
        }
        .user-message {
            width: 35%;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 5px;
            border: 1px solid #ccc;
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

    <?php
    // Verificar si se encontraron los datos del usuario
    if ($row = pg_fetch_assoc($result)) {
        // Mostrar los datos del usuario en un formulario para permitir la edición
        echo "<h2>Datos Personales</h2>";
        echo "<form method='POST' action=''>";
        echo "<table>";
        echo "<tr><th>Datos</th><th>Usuario</th></tr>";
        echo "<tr><td>RUT</td><td>" . htmlspecialchars($row['rut']) . "</td></tr>";
        echo "<tr><td>Nombre</td><td><input type='text' name='nombre' value='" . htmlspecialchars($row['nombre']) . "'></td></tr>";
        echo "<tr><td>Correo</td><td><input type='email' name='correo' value='" . htmlspecialchars($row['correo']) . "'></td></tr>";
        echo "<tr><td>Empresa</td><td>" . htmlspecialchars($row['empresa']) . "</td></tr>";
        echo "<tr><td>Área</td><td>" . htmlspecialchars($row['area']) . "</td></tr>";
        echo "<tr><td>Cargo</td><td>" . htmlspecialchars($row['cargo']) . "</td></tr>";
        echo "<tr><td>Tipo de Usuario</td><td>" . htmlspecialchars($row['tipo_usuario']) . "</td></tr>";
        echo "<tr><td>Última conexión</td><td>" . htmlspecialchars($row['ultima_conexion']) . "</td></tr>";
        echo "<tr><td>Última Actualización de Contraseña</td><td>" . htmlspecialchars($row['ultima_modificacion_contrasena']) . "</td></tr>";
        echo "</table>";
        echo "<input type='submit' value='Actualizar' class='btn btn-primary'>";
        echo "</form>";
    } else {
        echo "No se encontraron datos para el usuario.";
    }
    ?>
</div>

</body>
</html>

<?php
// Cerrar la conexión a la base de datos
pg_close($db);
?>
