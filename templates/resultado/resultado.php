<?php

include('../../navbar.php');
include ('../../error_control.php');



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['rut'])) {
    // Redirigir al usuario al formulario de inicio de sesión o mostrar un mensaje de error
    header("Location: login.php");
    exit();
}

$rut_del_usuario = $_SESSION['rut'];
$examen_id = $_GET['examen_id'];

include '../../db/db.php';

// Conexión a la base de datos
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

$_SESSION['rut'] = $rut_del_usuario;

if (isset($_GET['resultado'])) {
    $resultado = $_GET['resultado'];

    $rut = $_SESSION["rut"];

    // Consulta para eliminar registros de la tabla 'respuestas_usuario' según el 'rut'
    $sql = "DELETE FROM respuestas_usuario WHERE rut_usuario = '$rut'";

    // Ejecutar la consulta
    $result = pg_query($db, $sql);

  
    $total_preguntas = "SELECT COUNT(*) FROM preguntas WHERE examen_id = $examen_id";
    $result_total_preguntas = pg_query($db, $total_preguntas);

    // Obtener el número total de preguntas
    $row = pg_fetch_row($result_total_preguntas);
    $total_preguntas_value = $row[0];

    $total_preguntas_value1 = ($resultado / $total_preguntas_value) * 100;


    
    // Mostrar el resultado
    if ($resultado >= 8) {
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/login.css">
        <title>Examen</title>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <div style="background-color: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 10px;">';
        echo "¡Felicidades! Has aprobado el examen con $resultado de $total_preguntas_value respuestas correctas.  has tenido un $total_preguntas_value1% Para continuar con un nuevo curso ingrese a Inicio en la barra de navegación.";
        echo '</div>';
        echo '<div style="text-align: center;">'; // Abre un nuevo div para centrar la imagen
        echo '<img src="/Portal_Capacitacion/templates/img/aprobaste.gif" alt="Descripción de la imagen" style="margin-left: auto; margin-right: auto;">';
        echo '</div>'; // Cierra el div para centrar la imagen
    } else {
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/login.css">
        <title>Examen</title>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <div style="background-color: #f44336; color: white; padding: 20px; text-align: center; border-radius: 10px;">';
        echo "Lo siento, has reprobado el examen con $resultado de $total_preguntas_value respuestas correctas.  has tenido un $total_preguntas_value1% de 100% en puntuación Por favor, ingrese a Inicio en la barra de navegación y vuelva a intentar.";
        echo '</div>';

        echo '<div style="text-align: center;">'; // Abre un nuevo div para centrar la imagen
echo '<img src="/Portal_Capacitacion/templates/img/reprobado.jpg" alt="Descripción de la imagen" style="margin-left: auto; margin-right: auto;">';
echo '</div>'; // Cierra el div para centrar la imagen
        
    }
} else {
    // Si la variable $resultado no está definida, mostrar un mensaje de error
    echo '<div style="background-color: #f44336; color: white; padding: 20px; text-align: center; border-radius: 10px;">';
    echo "Error: No se ha proporcionado el resultado del examen.";
    echo '</div>';
}


?>
