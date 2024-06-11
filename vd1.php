<?php

include 'check_password.php';
include 'db/db.php';
include 'error_control.php';

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
if(isset($_GET['id'])) {
    // Recupera el ID del curso
    $curso_id = $_GET['id'];
} else {
    // Si no se proporcionó un ID válido, puedes redirigir al usuario o mostrar un mensaje de error
    echo "Error: No se proporcionó un ID válido";
}

// Determina qué video mostrar según el ID del curso
if($curso_id == 1) {
    $video_src = "vd1_prueba.mp4";
} elseif($curso_id == 2) {
    $video_src = "vd2_prueba.mp4";
} elseif($curso_id > 2) {
    $video_src = "video_prueba.mp4";
} else {
    // Si el ID del curso no coincide con ninguna opción válida, puedes mostrar un mensaje de error o manejarlo de acuerdo a tus necesidades
    echo "No se encontró un video para el ID del curso proporcionado.";
    exit; // Termina la ejecución del script
}

$rut_del_usuario = $_SESSION['rut'];

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Verificar si el usuario ha realizado el curso actual
$curso_actual = "Curso prevención de delitos";
$query_estado_examen = "SELECT * FROM estado_examen WHERE rut = $1 AND nombre_cur = $2";
$resultado_estado_examen = pg_query_params($conn, $query_estado_examen, array($rut_del_usuario, $curso_actual));

// Verificar si se encontraron resultados en la consulta
if (pg_num_rows($resultado_estado_examen) > 0) {
    $row = pg_fetch_assoc($resultado_estado_examen);
    $estado_examen = $row['estado'];
} else {
    $estado_examen = "Sin realizar";
}

// Prepara la consulta SQL para obtener el nombre del curso
$consulta = "SELECT nombre_cur FROM cursos WHERE id_cur = $curso_id";

// Ejecuta la consulta
$resultado = pg_query($conn, $consulta);

// Verifica si la consulta se ejecutó correctamente
if ($resultado) {
    // Intenta obtener el nombre del curso y maneja cualquier error
    try {
        $fila = pg_fetch_assoc($resultado);
        if (!$fila || !isset($fila['nombre_cur'])) {
            throw new Exception("No se encontró el curso.");
        }
        $nombre_curso = $fila['nombre_cur'];
    } catch (Exception $e) {
        header("Location: 404.php");
        exit();
    }
} else {
    // Redirige a 404.php si la consulta falla
    header("Location: 404.php");
    exit();
}




function obtener_clase_estado($estado_examen) {
    switch ($estado_examen) {
        case 'Realizado':
            return 'realizado';
        case 'Pendiente':
            return 'pendiente';
        case 'Aprobado':
            return 'aprobado';
        case 'Reprobado':
            return 'reprobado';
        default:
            return 'sin-realizar';
    }
}

// Determinar si el botón de descarga de contenido debe estar deshabilitado
$boton_descarga_deshabilitado = ($estado_examen === 'Aprobado');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modelo prevención de delito</title>
    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/vd1.css">

</head>

<body>
    <section>
        <div class="color"></div>
        <div class="color"></div>
        <div class="color"></div>
        <div class="box">
            <div class="container">
                <div>
                    <div class="form1">
                        <div class="inputBox1" style="text-align: center; margin-bottom:30px;">
                            <a href="/Portal_Capacitacion/templates/cursos/video_curso.php?id=<?php echo $curso_id; ?>"> <input type="submit" value="Volver"/> </a>
                        </div>
                    </div>
                    <div class="estado-examen">
                            <p>Estado del examen:</p>
                            <span id="estado-examen" class="<?php echo obtener_clase_estado($estado_examen); ?>">
                                <?php echo $estado_examen; ?>
                            </span>
                        </div>
                </div>
              
<div class="form">
    <div align="center">
        <h2><?php echo $nombre_curso; ?></h2>
    </div>
    <div class="video-container">
        <video controls class="video">
            <source src="templates\FilesWeb\<?php echo $video_src; ?>" type="video/mp4">
            Tu navegador no soporta el elemento de video.
        </video>
    </div>
    <div class="inputBox">
        <a href="/Portal_Capacitacion/templates/descarga/Curso_MPD.pdf" target="blank">
            <input type="submit" value="Descargar contenido" id="pdfButton"/>
        </a>
            <?php if ($estado_examen === 'Aprobado'): ?>
                <p>¡Ya aprobaste el examen, la opción de realizar examen está deshabilitada!</p>
            <?php else: ?>
                <a href="/Portal_Capacitacion/templates/preguntas/test.php?id_cur=<?php echo $curso_id; ?>" id="realizarExamenButton">
                <input type="submit" value="Realizar examen" id="examButton"/>
                </a>
            <?php endif;?>
    </div>
</div>
    </section>

    <script src="/Portal_Capacitacion/templates/js/vd1.js">
  </script>  


</body>
</html>
