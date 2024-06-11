<?php

include('../../navbar.php');
include('../../error_control.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['rut'])) {
    // Redirigir al usuario al formulario de inicio de sesión o mostrar un mensaje de error
    header("Location: login.php");
    exit();
}

// Verifica si se ha pasado un parámetro de ID en la URL
if(isset($_GET['variable'])) {
    // Recupera el ID del curso
    $variable_php = $_GET['variable'];
    echo "ID del curso seleccionado: " . $variable_php;
  } else {
    // Si no se proporcionó un ID válido, puedes redirigir al usuario o mostrar un mensaje de error
    echo "Error: No se proporcionó un ID válido";
  }

$rut_del_usuario = $_SESSION['rut'];

include '../../db/db.php';

// Conexión a la base de datos
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

$_SESSION['rut'] = $rut_del_usuario;


// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener las respuestas del formulario
    $respuestas = $_POST['respuestas'];
    $rut = $_SESSION["rut"];

    $query_update_status = "UPDATE estado_examen SET estado = 'Pendiente' WHERE rut = '$rut'";
    $result_update_status = pg_query($db, $query_update_status);

    // Guardar las respuestas en la tabla respuestas_usuario
    foreach ($respuestas as $id_pregunta => $respuesta) {
        $query_delete = "DELETE FROM respuestas_usuario WHERE rut_usuario = '$rut' AND id_pregunta = $id_pregunta";
        $result_delete = pg_query($db, $query_delete);

        $query_insert = "INSERT INTO respuestas_usuario (id_pregunta, rut_usuario, respuesta) VALUES ($id_pregunta, '$rut', '$respuesta')";
        $result_insert = pg_query($db, $query_insert);

        if (!$result_insert) {
            // Manejar el error en caso de falla en la inserción
            echo "Error al insertar la respuesta: " . pg_last_error($db);
        }
    }

    // Redirigir de vuelta a la página del examen después de guardar las respuestas
    header("Location: ../../vd1.php?id=$variable_php");
    exit();
}
