<?php

include '../../db/db.php';
include '../../error_control.php';

$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (isset($_POST['rut'])) {
    $rut = $_POST['rut'];

    $query_update_status = "UPDATE estado_examen SET estado = 'Pendiente' WHERE rut = '$rut'";
    $result_update_status = pg_query($db, $query_update_status);

    if ($result_update_status) {
        echo "Estado actualizado correctamente";
    } else {
        echo "Error al actualizar el estado";
    }
} else {
    echo "No se proporcionó el rut del usuario";
}

?>
