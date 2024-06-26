<?php

include 'check_password.php';
include 'db/db.php';
include 'error_control.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['rut'])) {
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])) {
    $curso_id = $_GET['id'];
} else {
    echo "Error: No se proporcionó un ID válido";
    exit();
}

$videos = [
    1 => "preview_vd1.mp4",
    2 => "preview_vd2.mp4",
    3 => "preview_vd3.mp4",
    4 => "preview_vd4.mp4",
    5 => "preview_vdIncapacidad.mp4",
    6 => "preview_vd6.mp4"
];

$video_src = $videos[$curso_id] ?? "video_prueba.mp4";

$rut_del_usuario = $_SESSION['rut'];

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

$query_estado_examen = "SELECT * FROM estado_examen WHERE rut = $1 AND id_cur = $2";
$resultado_estado_examen = pg_query_params($conn, $query_estado_examen, array($rut_del_usuario, $curso_id));

if (pg_num_rows($resultado_estado_examen) > 0) {
    $row = pg_fetch_assoc($resultado_estado_examen);
    $estado_examen = $row['estado'];
} else {
    $estado_examen = "Sin realizar";
}

$consulta = "SELECT nombre_cur FROM cursos WHERE id_cur = $1";
$resultado = pg_query_params($conn, $consulta, array($curso_id));

if ($resultado) {
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
                            <source src="/Portal_Capacitacion/templates/FilesWeb/<?php echo $video_src; ?>" type="video/mp4">
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
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="/Portal_Capacitacion/templates/js/vd1.js"></script>  
</body>
</html>
