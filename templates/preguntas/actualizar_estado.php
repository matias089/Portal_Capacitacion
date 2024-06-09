<?php

include '../../db/db.php';

// Conexión a la base de datos
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Verificar si se recibió el rut del usuario
if (isset($_POST['rut'])) {
    $rut = $_POST['rut'];

    // Actualizar el estado del examen en la tabla estado_examen
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
