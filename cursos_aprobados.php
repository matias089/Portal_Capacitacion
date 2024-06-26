<?php
session_start();
include 'error_control.php';
if (!isset($_SESSION['tipo_usuario'])) {
    header("Location: portada.html");
    exit();
}

include 'db/db.php';
include 'navbar.php';

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

$rut = $_SESSION['rut'];

$query = "SELECT cursos.nombre_cur, estado_examen.id_cur 
          FROM estado_examen 
          JOIN cursos ON estado_examen.id_cur = cursos.id_cur 
          WHERE estado_examen.rut = '$rut' AND estado_examen.estado = 'Aprobado'";
$result = pg_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/certificados_aprobados.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>Certificados del Usuario</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/index.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
    }
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f8f8f8;
        padding: 10px;
        border-bottom: 1px solid #ccc;
    }
    .content {
        padding: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #f0f0f0;
    }
    .approved {
        color: green;
    }
    .pdf-button {
        padding: 5px 10px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    .pdf-button:hover {
        background-color: #45a049;
    }
</style>
<body>

<div class="content">
    <h2>Certificados del usuario:</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre del curso</th>
                <th>Estado</th>
                <th>Descargar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result) {
                while ($row = pg_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['nombre_cur']}</td>
                            <td class='approved'>Aprobado</td>
                            <td><a class='pdf-button' href='certificade_generator.php?id_cur={$row['id_cur']}'>PDF</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No se encontraron cursos aprobados.</td></tr>";
            }
            pg_free_result($result);
            pg_close($conn);
            ?>
        </tbody>
    </table>
</div>
</body>
</html>