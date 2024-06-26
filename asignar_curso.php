<?php

include 'error_control.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['ruts_seleccionados']) && !empty($_POST['ruts_seleccionados'])) {

        $ruts_seleccionados = $_POST['ruts_seleccionados'];

        if (isset($_POST['curso']) && !empty($_POST['curso'])) {
            $curso_seleccionado = $_POST['curso'];

            include 'db/db.php';
            $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
            if (!$conn) {
                echo "Error de conexión.";
                exit;
            }

            foreach ($ruts_seleccionados as $rut) {
                $rut = pg_escape_string($conn, $rut);
                $curso_seleccionado = pg_escape_string($conn, $curso_seleccionado);

                $query = "SELECT COUNT(*) AS count FROM usuarios_cursos WHERE rut_usuario = '$rut' AND nombre_cur = '$curso_seleccionado'";
                $result = pg_query($conn, $query);
                if (!$result) {
                    echo "Error al verificar el registro en la base de datos.";
                    exit;
                }
                $row = pg_fetch_assoc($result);
                $count = $row['count'];

                if ($count == 0) {
                    $query = "INSERT INTO usuarios_cursos (rut_usuario, nombre_cur) VALUES ('$rut', '$curso_seleccionado')";
                    $result = pg_query($conn, $query);
                    if (!$result) {
                        echo "Error al guardar los datos en la base de datos.";
                        exit;
                    }
                }
            }
            pg_close($conn);
            header("Location: administrar.php?mensaje=exito");
            exit;
        } else {
            header("Location: administrar.php?mensaje=no_curso");
            exit;
        }
    } else {
        header("Location: administrar.php?mensaje=no_ruts");
        exit;
    }
} else {
    header("Location: administrar.php?mensaje=no_enviado");
    exit;
}
?>
