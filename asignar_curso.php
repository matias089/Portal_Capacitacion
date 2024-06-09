<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se han seleccionado ruts
    if (isset($_POST['ruts_seleccionados']) && !empty($_POST['ruts_seleccionados'])) {
        // Recuperar los ruts seleccionados
        $ruts_seleccionados = $_POST['ruts_seleccionados'];

        // Verificar si se ha seleccionado un curso
        if (isset($_POST['curso']) && !empty($_POST['curso'])) {
            // Recuperar el curso seleccionado
            $curso_seleccionado = $_POST['curso'];

            // Conexión a la base de datos
            include 'db/db.php';
            $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
            if (!$conn) {
                echo "Error de conexión.";
                exit;
            }

            foreach ($ruts_seleccionados as $rut) {
                // Escapar los datos para prevenir inyección SQL
                $rut = pg_escape_string($conn, $rut);
                $curso_seleccionado = pg_escape_string($conn, $curso_seleccionado);

                // Verificar si ya existe el registro en la tabla usuarios_cursos
                $query = "SELECT COUNT(*) AS count FROM usuarios_cursos WHERE rut_usuario = '$rut' AND nombre_cur = '$curso_seleccionado'";
                $result = pg_query($conn, $query);
                if (!$result) {
                    echo "Error al verificar el registro en la base de datos.";
                    exit;
                }
                $row = pg_fetch_assoc($result);
                $count = $row['count'];

                // Si el registro no existe, insertarlo en la tabla usuarios_cursos
                if ($count == 0) {
                    $query = "INSERT INTO usuarios_cursos (rut_usuario, nombre_cur) VALUES ('$rut', '$curso_seleccionado')";
                    $result = pg_query($conn, $query);
                    if (!$result) {
                        echo "Error al guardar los datos en la base de datos.";
                        exit;
                    }
                }
            }

            // Cerrar la conexión
            pg_close($conn);

            // Redirigir a la página "administrar.php" con un mensaje exitoso
            header("Location: administrar.php?mensaje=exito");
            exit;
        } else {
            // No se ha seleccionado un curso
            header("Location: administrar.php?mensaje=no_curso");
            exit;
        }
    } else {
        // No se han seleccionado ruts
        header("Location: administrar.php?mensaje=no_ruts");
        exit;
    }
} else {
    // El formulario no ha sido enviado
    header("Location: administrar.php?mensaje=no_enviado");
    exit;
}
?>
