<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chequeo de Clave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
</head>
<body>

<?php    
include 'db/db.php';

$conexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conexion) {
    echo "Error al conectar a la base de datos.";
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['tipo_usuario'])) {
    header("Location: portada.html");
    exit(); 
}

$rut = $_SESSION['rut'];

$query = "SELECT rut, ultima_modificacion_contrasena FROM usuarios WHERE rut = '$rut'";
$resultado = pg_query($conexion, $query);

if (!$resultado) {
    echo "Error al ejecutar la consulta.";
    exit;
}

$tiempoMaximo = 3 * 30 * 24 * 60 * 60;

$fechaActual = time();

$fila = pg_fetch_assoc($resultado);

if (!$fila) {
    echo "No se encontró ningún usuario con el rut especificado.";
    exit;
}

$rutUsuario = $fila['rut'];
$ultimaModificacion = strtotime($fila['ultima_modificacion_contrasena']);
$diferenciaTiempo = $fechaActual - $ultimaModificacion;

if ($diferenciaTiempo > $tiempoMaximo) {
    echo '<div id="alertContainer" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 999; display: flex; justify-content: center; align-items: center;">
            <div id="alertMessage" class="alert alert-warning alert-dismissible fade show" role="alert" style="max-width: 500px;">
              Estimado usuario con rut <strong>' . $rutUsuario . '</strong>, hace más de 3 meses que no cambias tu contraseña. 
              Por favor, <a href="newpw.php?rutUsuario=' . $rutUsuario . '" class="alert-link">cambia tu contraseña</a> lo antes posible.
              <button id="closeButton" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>';
}else{

}

pg_close($conexion);
?>
</body>
<script src="/Portal_Capacitacion/templates/js/check_password.js">
  </script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-Atj8AqpiyZgywYrvKRb8+TRyS5fz5UOCZKZ8Qolcb5bo1T/QQ0koVJ/1Cb9tz+X9" crossorigin="anonymous"></script>
</html>