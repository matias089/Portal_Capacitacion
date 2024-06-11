<?php
// Inicia la sesión si no está iniciada
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['tipo_usuario'])) {
    // Si el usuario no está logueado, redirige a la página de login
    header("Location: portada.html");
    exit(); // Es importante salir del script después de redirigir
}

// Verifica el tipo de usuario
//$tipo_usuario = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : '';

// Incluye el contenido del navbar
include 'navbar.php';
include 'check_password.php';

// Comprobación de la sesión e impresión del mensaje de sesión iniciada
//echo "Sesión iniciada para el usuario {$_SESSION['rut']}"; // Esto debería imprimir el ID de usuario si la sesión se inicia correctamente
 
// Conexión a la base de datos
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
 
if (!$db) {
  echo "Error al conectar a la base de datos.";
  exit;
}

// Obtener el rut del usuario iniciado de la sesión
$rut_usuario = $_SESSION['rut'];

// Consulta SQL para seleccionar los cursos asignados al usuario
$sql = "SELECT c.id_cur, c.nombre_cur FROM usuarios_cursos uc JOIN cursos c ON uc.nombre_cur = c.nombre_cur WHERE uc.rut_usuario = '$rut_usuario'";

$result = pg_query($db, $sql);

if (!$result) {
  echo "Error al ejecutar la consulta.";
  exit;
}


?>

<!DOCTYPE html>
<html style="font-size: 16px;" lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>index</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/index.css">

    <style>

.active.carousel-item-next:not(.carousel-item-start),
        .active.carousel-item-start,
        .carousel-item-next:not(.carousel-item-start) {
            transform: translateX(0%);
        }

        .active.carousel-item-start,
        .carousel-item-prev:not(.carousel-item-end) {
            transform: translateX(0%);
        }

        .image-list a:hover img {
            transform: scale(1.1); /* Aumenta la escala de la imagen al pasar el cursor */
            transition: transform 0.3s ease; /* Agrega una transición suave */
        }

        /* Estilos adicionales para hacer el contenido más receptivo */
        .image-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Centra las imágenes horizontalmente */
        }

        .image-list a {
            flex: 0 0 calc(33.333% - 20px); /* Cada imagen ocupará aproximadamente un tercio del contenedor */
            margin: 10px; /* Margen entre las imágenes */
            text-align: center;
        }

        .image-list img {
            width: 100%;
            height: auto;
        }

        @media (max-width: 768px) {
            .image-list a {
                flex: 0 0 calc(50% - 20px); /* En pantallas más pequeñas, cada imagen ocupará la mitad del contenedor */
            }
          }

  </style>

</head>

<body>

<div class="color"></div>
<div class="color"></div>
<div class="color"></div>


<div id="carrusel-container" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <video class="d-block w-100" data-autoplay="1" loop muted autoplay playsinline>
          <source src="/Portal_Capacitacion/templates/FilesWeb/preview_vd1.mp4" type="video/mp4">
          Tu navegador no soporta el elemento de video.
      </video>
    </div>
    <div class="carousel-item">
      <video class="d-block w-100" data-autoplay="1" loop muted autoplay playsinline>
          <source src="/Portal_Capacitacion/templates/FilesWeb/preview_vd2.mp4" type="video/mp4">
          Tu navegador no soporta el elemento de video.
      </video>
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="/Portal_Capacitacion/templates/img/COVAL-SSFF-PRESENTACION-JEFES-COMERCIALES-UAF1.png" alt="Third slide">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carrusel-container" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carrusel-container" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

<section class="u-align-center u-clearfix u-section-2" id="sec-829f">

  <div>
    <div class="u-clearfix u-sheet u-sheet-1">
      <h2 class="u-align-left u-text u-text-default u-text-white u-text-1">Cursos Capacitación de Compliance: </h2>
    </div>

    <div id="image-list" class="image-list" style="margin-left: 30px;">
    <?php 
    
    if (pg_num_rows($result)==0){
    echo '<div class="alert alert-warning" role="alert">No hay cursos asignados a esta categoría.</div>';
    }else{
    // Generar HTML para mostrar las imágenes de los cursos asignados
	while ($row = pg_fetch_assoc($result)) {
	  $id_cur = $row['id_cur'];
	  $nombre_cur = $row['nombre_cur'];
	  $imagen_src = "/Portal_Capacitacion/templates/img/" . str_replace(" ", "", $nombre_cur) . ".png"; // Ajusta esto según la estructura de tu directorio de imágenes
	  
	  echo "<a href=\"/Portal_Capacitacion/templates/cursos/video_curso.php?id=$id_cur\">";
	  echo "<img src=\"$imagen_src\" alt=\"Imagen de $nombre_cur\">";
	  echo "<div class=\"image-description\">$nombre_cur</div>";
	  echo "</a>";
	}
    }
    ?>
  </div>

  <div>
    <div class="u-clearfix u-sheet u-sheet-1">
      <h2 class="u-align-left u-text u-text-default u-text-white u-text-1">Cursos Capacitación de Recursos Humanos: </h2>
    </div>

    <div id="image-list" class="image-list" style="margin-left: 30px;">
    <a href="/Portal_Capacitacion/templates/cursos/video_curso.php?id=4">
      <img src="/Portal_Capacitacion/templates/img/ProcesoRecursoHumano.png" alt="Imagen 4">
      <div class="image-description">Recursos Humanos</div>
    </a>
    <a href="/Portal_Capacitacion/templates/cursos/video_curso.php?id=5">
      <img src="/Portal_Capacitacion/templates/img/GestionRecursoHumano.png" alt="Imagen 5">
      <div class="image-description">Gestion Recursos Humanos</div>
    </a>
    <a href="/Portal_Capacitacion/templates/cursos/video_curso.php?id=6">
      <img src="/Portal_Capacitacion/templates/img/RecursoHumano.png" alt="Imagen 6">
      <div class="image-description">Recursos Humanos</div>
    </a>
  </div>

  <script src="/Portal_Capacitacion/templates/js/index.js">
  </script>  
</body>

</html>

<?php 

// Liberar el resultado
pg_free_result($result);


// Cerrar la conexión
pg_close($db);
?>
