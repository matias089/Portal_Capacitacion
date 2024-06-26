<?php

include('../../navbar.php');


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
if(isset($_GET['id_cur'])) {
    // Recupera el ID del curso
    $id_examen = $_GET['id_cur'];
} else {
    // Si no se proporcionó un ID válido, puedes redirigir al usuario o mostrar un mensaje de error
    echo "Error: No se proporcionó un ID válido";
}

$rut_del_usuario = $_SESSION['rut'];

include '../../db/db.php';

// Conexión a la base de datos
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Función para obtener las preguntas y opciones de respuesta desde la base de datos
function obtenerPreguntas($db, $id_examen)
{
    $query = "SELECT * FROM preguntas where examen_id = $id_examen";
    $result = pg_query($db, $query);
    return pg_fetch_all($result);
}
// Mostrar el formulario para realizar el examen
$preguntas = obtenerPreguntas($db, $id_examen);

// Función para obtener el nombre del curso desde la base de datos
function obtenerTitulo($db, $id_examen)
{
    $query = "SELECT nombre_cur FROM cursos WHERE id_cur = $id_examen";
    $result = pg_query($db, $query);
    return pg_fetch_assoc($result);
}

// Mostrar el formulario para realizar el examen
$titulo = obtenerTitulo($db, $id_examen);

 
// Función para procesar el examen
function procesarExamen($respuestas, $db)
{
    $correctas = 0;
    foreach ($respuestas as $id_pregunta => $respuesta) {
        $query = "SELECT respuesta_correcta FROM preguntas WHERE id = $id_pregunta";
        $result = pg_query($db, $query);
        $respuesta_correcta = pg_fetch_assoc($result)['respuesta_correcta'];
        if ($respuesta == $respuesta_correcta) {
            $correctas++;
        }
    }
    return $correctas;
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener las respuestas del formulario
    $respuestas = $_POST['respuestas'];

    // Procesar el examen
    $resultado = procesarExamen($respuestas, $db);
    $rut = $_SESSION["rut"];
        // Eliminar las respuestas anteriores del usuario
        $query_delete_anterior = "DELETE FROM respuestas_usuario WHERE rut_usuario = '$rut'";
        $result_delete_anterior = pg_query($db, $query_delete_anterior);
        if (!$result_delete_anterior) {
            echo "Error al eliminar las respuestas anteriores: " . pg_last_error($db);
        }
    
        // Guardar las nuevas respuestas en la tabla respuestas_usuario
        foreach ($respuestas as $id_pregunta => $respuesta) {
            $query_insert = "INSERT INTO respuestas_usuario (id_pregunta, rut_usuario, respuesta) VALUES ($id_pregunta, '$rut', '$respuesta')";
            $result_insert = pg_query($db, $query_insert);
            if (!$result_insert) {
                echo "Error al insertar la respuesta: " . pg_last_error($db);
            }
        }
    
    if ($resultado >= 8) {
        $estado_examen = 'Aprobado';
        foreach ($respuestas as $id_pregunta => $respuesta) {
            $query_delete = "DELETE FROM respuestas_usuario WHERE rut_usuario = '$rut' AND id_pregunta = $id_pregunta";
            $result_delete = pg_query($db, $query_delete);
        }
    } else {
        $estado_examen = 'Reprobado';
        foreach ($respuestas as $id_pregunta => $respuesta) {
            $query_delete = "DELETE FROM respuestas_usuario WHERE rut_usuario = '$rut' AND id_pregunta = $id_pregunta";
            $result_delete = pg_query($db, $query_delete);
        }
    }

    if ($resultado >= 8) {
        $estado_examen = 'Aprobado';
    } else {
        $estado_examen = 'Reprobado';
    }
    
$query_select = "SELECT * FROM estado_examen WHERE rut = '$rut' AND id_cur = $id_examen";
$result_select = pg_query($db, $query_select);

if (pg_num_rows($result_select) == 0) {
    $query_insert_estado = "INSERT INTO estado_examen (estado, rut, id_cur) VALUES ('$estado_examen', '$rut', $id_examen)";
    $result_insert_estado = pg_query($db, $query_insert_estado);
    if (!$result_insert_estado) {
        echo "Error al insertar el registro: " . pg_last_error($db);
    }
} else {
    $query_update_estado = "UPDATE estado_examen SET estado = '$estado_examen' WHERE rut = '$rut' AND id_cur = $id_examen";
    $result_update_estado = pg_query($db, $query_update_estado);
    if (!$result_update_estado) {
        echo "Error al actualizar el registro: " . pg_last_error($db);
    }
}
    foreach ($respuestas as $id_pregunta => $respuesta) {
        $query_delete = "DELETE FROM respuestas_usuario WHERE rut_usuario = '$rut' AND id_pregunta = $id_pregunta";
        $result_delete = pg_query($db, $query_delete);

        $query_insert = "INSERT INTO respuestas_usuario (id_pregunta, rut_usuario, respuesta) VALUES ($id_pregunta, '$rut', '$respuesta')";
        $result_insert = pg_query($db, $query_insert);

        if (!$result_insert) {
            echo "Error al insertar la respuesta: " . pg_last_error($db);
        }
    }
    header("Location: ../resultado/resultado.php?resultado=$resultado&examen_id=$id_examen");

    exit();


}

$respuestas_temporales = [];
$query_respuestas_temporales = "SELECT * FROM respuestas_usuario WHERE rut_usuario = '$rut_del_usuario'";
$result_respuestas_temporales = pg_query($db, $query_respuestas_temporales);
while ($row = pg_fetch_assoc($result_respuestas_temporales)) {
    $respuestas_temporales[$row['id_pregunta']] = $row['respuesta'];
}

$preguntas = obtenerPreguntas($db,$id_examen);
?>
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
<link rel="stylesheet" href="/Portal_Capacitacion/templates/css/login.css">
<title>Examen</title>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<style>

    p{
        font-size: 1.7rem;
    }
  
  body{
    background: #f2f3fd;
    background: linear-gradient(to right, #565768, #e8eafa);
  }
  .bg{
    background-position: center center;
  }

  .container {
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
width: 800vh;
min-height: 65vh;
background: rgba(255, 255, 255, 0.1);
border-radius: 10px;
display: flex;
justify-content: center;
align-items: center;
backdrop-filter: blur(5px);
box-shadow: 0 25px 45px rgba(0,0,0,0.1);
border: 1px solid rgba(255, 255, 255, 0.5);
border-right: 1px solid rgba(255, 255, 255, 0.2);
border-bottom: 1px solid rgba(255, 255, 255, 0.2);


}
.pregunta {
        display: none;
    }
    .pregunta.active {
        display: block;
    }
    .boton_personalizado {
        width: 45%;
        background: rgba(255,255,255,0.2);
        border: none;
        outline: none;
        padding: 10px 10px;
        border-radius: 35px;
        border: 1px solid rgba(255,255,255,0.5);
        border-right: 1px solid rgba(255,255,255,0.2);
        border-bottom: 1px solid rgba(255,255,255,0.2);
        font-size: 16px;
        letter-spacing: 1px;
        color: #1b1818;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin: 10px 10px 10px
        
    }
   
    .boton_personalizado:hover {
        background: #2082dd;
        color: #ffffff;
    }

    .radio-input {
display: flex;
flex-direction: column;
gap: 10px;
}

.value-radio {
display: none;
}

.value-radio-label {
display: flex;
align-items: center;
gap: 8px;
padding: 8px 12px;
border: 2px solid #4d4d4d;
border-radius: 20px;
color: #ccc;
background-color: #333;
cursor: pointer;
transition: all 0.3s;
}

.value-radio:checked + .value-radio-label {
border-color: #00b4ff;
background-color: #00b4ff;
color: #fff;
}

.value-radio-label::before {
content: '';
display: inline-block;
width: 16px;
height: 16px;
border-radius: 50%;
border: 2px solid #4d4d4d;
transition: all 1s;
}

.value-radio:checked + .value-radio-label::before {
border-color: #0175a6;
background-color: #ffffff;
}

</style>

</head>
<body>
<div class="color"></div>
<div class="color"></div>
<div class="color"></div>

<div class="container">
<form id="evalua" method="post" action="">
<div align="center" style="margin-bottom: 5vh;" >
<h1><?php echo $titulo['nombre_cur']; ?></h1>
</div>
<p> Pregunta <span id="contadorPreguntas">1</span>/<?php echo count($preguntas); ?> </p>
<?php foreach ($preguntas as $index => $pregunta): ?>
    <div class="pregunta <?php echo $index === 0 ? 'active' : ''; ?>" id="pregunta<?php echo $index + 1; ?>">
        <p><?php echo $pregunta['pregunta']; ?></p>
        <div class="radio-input">
            <input class="value-radio" name="respuestas[<?php echo $pregunta['id']; ?>]" id="option1_<?php echo $index + 1; ?>" type="radio" value="1" <?php if(isset($respuestas_temporales[$pregunta['id']]) && $respuestas_temporales[$pregunta['id']] == '1') echo 'checked'; ?> required>
            <label class="value-radio-label" for="option1_<?php echo $index + 1; ?>"><?php echo $pregunta['opcion_1']; ?></label>

            <input class="value-radio" name="respuestas[<?php echo $pregunta['id']; ?>]" id="option2_<?php echo $index + 1; ?>" type="radio" value="2" <?php if(isset($respuestas_temporales[$pregunta['id']]) && $respuestas_temporales[$pregunta['id']] == '2') echo 'checked'; ?> required>
            <label class="value-radio-label" for="option2_<?php echo $index + 1; ?>"><?php echo $pregunta['opcion_2']; ?></label>

            <?php if (!is_null($pregunta['opcion_3'])): ?>
                <input class="value-radio" name="respuestas[<?php echo $pregunta['id']; ?>]" id="option3_<?php echo $index + 1; ?>" type="radio" value="3" <?php if(isset($respuestas_temporales[$pregunta['id']]) && $respuestas_temporales[$pregunta['id']] == '3') echo 'checked'; ?> required>
                <label class="value-radio-label" for="option3_<?php echo $index + 1; ?>"><?php echo $pregunta['opcion_3']; ?></label>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<div align="center">
<button type="button" class="boton_personalizado" onclick="mostrarAnteriorPregunta()">Anterior</button><button type="button" class="boton_personalizado" id="btnSiguiente" onclick="mostrarSiguientePregunta() ">Siguiente</button>

<button type="submit" class="boton_personalizado" name="enviar_respuestas" onclick="return confirmarEnvio()">Enviar respuestas</button><button type="submit" class="boton_personalizado" name="guardar_salir" onclick="guardarRespuestas()" title="Guardar las respuestas y salir del examen. Puede regresar más tarde para completar el examen si es necesario.">Guardar y salir</button>
</div>
</form>
</div>


<script>
var preguntaActual = <?php echo $ultima_pregunta_respondida ?? 0; ?>;
var cantidadPreguntas = <?php echo count($preguntas); ?>;
var preguntas = document.querySelectorAll('.pregunta');

function mostrarPregunta(index) {
    if (index >= 0 && index < cantidadPreguntas) {
        preguntas[preguntaActual].classList.remove('active');
        preguntaActual = index;
        preguntas[preguntaActual].classList.add('active');
        actualizarContadorPreguntas();
    }
}

function mostrarSiguientePregunta() {
    if (preguntaActual < cantidadPreguntas - 1) {
        preguntas[preguntaActual].classList.remove('active');
        preguntaActual++;
        preguntas[preguntaActual].classList.add('active');
        actualizarContadorPreguntas();
    }
    if (preguntaActual === cantidadPreguntas - 1) {
        document.getElementById('btnSiguiente').style.display = 'none';
    }
}

function mostrarAnteriorPregunta() {
    if (preguntaActual > 0) {
        preguntas[preguntaActual].classList.remove('active');
        preguntaActual--;
        preguntas[preguntaActual].classList.add('active');
        actualizarContadorPreguntas();
    }
    if (preguntaActual < cantidadPreguntas - 1) {
        document.getElementById('btnSiguiente').style.display = '';
    }
}
function guardarRespuestas(id_examen) {
    var form = document.getElementById('evalua');
    var action = 'guardar_respuestas.php?variable=<?php echo urlencode($id_examen); ?>';
    form.setAttribute('action', action);
    form.submit();
}

function actualizarContadorPreguntas() {
    var contadorPreguntas = document.getElementById('contadorPreguntas');
    contadorPreguntas.textContent = preguntaActual + 1;
}

function confirmarEnvio() {
    var respuesta = confirm("¿Estás seguro de enviar tus respuestas? Si se envían, no podrás modificarlas.");
    return respuesta;
}

</script>
</div>
</body>
</html>
