<?php
// Inicia la sesión si no está iniciada
session_start();
include '../../error_control.php';

// Verifica si se ha pasado un parámetro de ID en la URL
if(isset($_GET['id'])) {
  // Recupera el ID del curso
  $curso_id = $_GET['id'];
  echo "ID del curso seleccionado: " . $curso_id;
} else {
  // Si no se proporcionó un ID válido, puedes redirigir al usuario o mostrar un mensaje de error
  echo "Error: No se proporcionó un ID válido";
}

// Verifica si el usuario está logueado
if (!isset($_SESSION['tipo_usuario'])) {
    // Si el usuario no está logueado, redirige a la página de login
    header("Location: '../../index.php'");
    exit(); // Es importante salir del script después de redirigir
}

    // Determina qué video mostrar según el ID del curso
    if($curso_id == 1) {
      $video_src = "../FilesWeb/preview_vd1.mp4";
  } elseif($curso_id == 2) {
      $video_src = "../FilesWeb/preview_vd2.mp4";
  } elseif($curso_id == 3) {
      $video_src = "../FilesWeb/preview_vd3.mp4";
  } elseif($curso_id == 4) {
      $video_src = "../FilesWeb/preview_vd4.mp4";
  } elseif($curso_id == 5) {
      $video_src = "../FilesWeb/preview_vdIncapacidad.mp4";
  } elseif($curso_id == 6) {
      $video_src = "../FilesWeb/preview_vd6.mp4";
  } elseif($curso_id > 6) {
      $video_src = "../FilesWeb/video_prueba.mp4";
  } else {
      // Si el ID del curso no coincide con ninguna opción válida, puedes mostrar un mensaje de error o manejarlo de acuerdo a tus necesidades
      echo "No se encontró un video para el ID del curso proporcionado.";
      exit; // Termina la ejecución del script
  }

// Incluye el archivo de conexión a la base de datos
//include('/var/www/html/Portal_Capacitacion/db/db.php');

include '../../db/db.php';

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Consultas SQL para obtener el nombre y la descripción del curso
 // Puedes cambiar este valor según sea necesario
$query_nombre_curso = "SELECT nombre_cur FROM cursos WHERE id_cur = $curso_id";
$query_descripcion_curso = "SELECT descripcion FROM cursos WHERE id_cur = $curso_id";

// Ejecutar las consultas
$result_nombre_curso = pg_query($conn, $query_nombre_curso);
$result_descripcion_curso = pg_query($conn, $query_descripcion_curso);

// Obtener los resultados de las consultas
$nombre_curso = pg_fetch_result($result_nombre_curso, 0, 0);
$descripcion_curso = pg_fetch_result($result_descripcion_curso, 0, 0);
?>
<!DOCTYPE html>
<html style="font-size: 16px;" lang="es">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="keywords" content="Curso CiberSeguridad 1​">
    <meta name="description" content="">
    <title>video_curso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="../FilesWeb/site_rework.css" media="screen">
<link rel="stylesheet" href="/video_curso.css" media="screen">
    <script class="u-script" type="text/javascript" src="../FilesWeb/jquery.js" defer=""></script>
    <script class="u-script" type="text/javascript" src="../FilesWeb/site.js" defer=""></script>
    <meta name="generator" content="">
    <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">
    
    <style>
      /* css de volver */
.form1 .inputBox1
{
    width: 100%;
    text-align: left;
    margin-top: 1px;
}

.box {
    position: relative;
    z-index: 1; /* Asegura que el botón esté por encima del video */
}

/* css de volver */
.form1 .inputBox1 input
{
    width: 86%;
    background: rgba(255,255,255,0.2);
    border: none;
    outline: none;
    padding: 10px 20px;
    border-radius: 35px;
    border: 1px solid rgba(255,255,255,0.5);
    border-right: 1px solid rgba(255,255,255,0.2);
    border-bottom: 1px solid rgba(255,255,255,0.2);
    font-size: 16px;
    letter-spacing: 1px;
    color: #1b1818;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);


}
/* css de volver */
.form1 .inputBox1 input[type="submit"]{
    background: #ffffff;
    color: #666;
    max-width: 200px;
    cursor: pointer;
    /*margin-bottom: 20px;*/
    font-weight: 600px;
}
/* css de volver */
.form1 .inputBox1 input[type="submit"]:hover{
    background: #2082dd;
    color: #ffffff;
}

.form1 .inputBox1
{
    width: 100%;
    text-align: left;
    margin-top: 20px;
}

.u-bottom-left {
  position: fixed;
  bottom: 0;
  left: 0;
}

@media (max-width: 768px) {
  .u-sheet {
    margin-bottom: 10vh; /* Ajusta el margen inferior en dispositivos móviles */
  }
}

    </style>
    
    
    <script type="application/ld+json">{
		"@context": "http://schema.org",
		"@type": "Organization",
		"name": ""
}</script>
    <meta name="theme-color" content="#478ac9">
    <meta property="og:title" content="video_curso">
    <meta property="og:type" content="website">
</head>

  <body data-path-to-root="./" data-include-products="false" class="u-body u-grey-80 u-xl-mode" data-lang="es">
    <section class="u-clearfix u-shading u-uploaded-video u-video u-section-1" id="sec-3f70">
    <div class="box">
            <div class="form1">
                <div class="inputBox1">
                    <a href="/Portal_Capacitacion/index.php"> <input type="submit" value="Volver"/> </a>
                </div>
            </div>
        </div>
      <div class="u-background-video u-video-contain" style="position: absolute !important;left: 0;top: 0;width: 100vw; height: 100vh;">
        <div class="embed-responsive" style="height: 100vh;">
          <video class="embed-responsive-item" data-autoplay="1" loop="" muted="1" autoplay="autoplay" playsinline="">
            <source src="<?php echo $video_src; ?>" type="video/mp4">
            <p>Your browser does not support HTML5 video.</p>
          </video>
        </div>
        <div class="u-video-shading" style="background-image: linear-gradient(0deg, rgba(0,0,0,0.5), rgba(0,0,0,0.5));"></div>
      </div>
      
      <div class="u-clearfix u-sheet u-bottom-left" style="margin-bottom: 10vh;">
  <h1 class="margen"><?php echo $nombre_curso; ?></h1>
  <p><?php echo $descripcion_curso; ?></p>
  <a href="/Portal_Capacitacion/vd1.php?id=<?php echo $curso_id; ?>" class="u-btn u-btn-round u-button-style u-file-link u-hover-palette-1-light-1 u-palette-1-base u-radius u-btn-1" style="margin-left: 3vw;">Iniciar</a>
</div>
    </section>
</body></html>

