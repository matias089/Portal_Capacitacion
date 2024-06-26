<?php

include('../../navbar.php');
include('../../error_control.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['rut'])) {
    header("Location: login.php");
    exit();
}

if(isset($_GET['variable'])) {
    $variable_php = $_GET['variable'];
    echo "ID del curso seleccionado: " . $variable_php;
  } else {
    echo "Error: No se proporcionó un ID válido";
  }

$rut_del_usuario = $_SESSION['rut'];

include '../../db/db.php';


$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

$_SESSION['rut'] = $rut_del_usuario;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $respuestas = $_POST['respuestas'];
    $rut = $_SESSION["rut"];
    $id_cur = $variable_php;

    $query_check_rut = "SELECT 1 FROM estado_examen WHERE rut = '$rut' AND id_cur = $id_cur";
    $result_check_rut = pg_query($db, $query_check_rut);

    if (pg_num_rows($result_check_rut) > 0) {
        $query_update_status = "UPDATE estado_examen SET estado = 'Pendiente' WHERE rut = '$rut' AND id_cur = $id_cur";
        $result_update_status = pg_query($db, $query_update_status);
    } else {
        $query_insert_status = "INSERT INTO estado_examen (estado, rut, id_cur) VALUES ('Pendiente', '$rut', '$id_cur')";
        $result_insert_status = pg_query($db, $query_insert_status);
    }

    foreach ($respuestas as $id_pregunta => $respuesta) {
        $query_delete = "DELETE FROM respuestas_usuario WHERE rut_usuario = '$rut' AND id_pregunta = $id_pregunta";
        $result_delete = pg_query($db, $query_delete);

        $query_insert = "INSERT INTO respuestas_usuario (id_pregunta, rut_usuario, respuesta) VALUES ($id_pregunta, '$rut', '$respuesta')";
        $result_insert = pg_query($db, $query_insert);

        if (!$result_insert) {
            echo "Error al insertar la respuesta: " . pg_last_error($db);
        }
    }

    header("Location: ../../vd1.php?id=$variable_php");
    exit();
}
