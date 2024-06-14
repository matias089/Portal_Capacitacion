<?php

include('db/db.php');


// Verifica si la sesión no está iniciada y, de ser así, la inicia
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si hay un usuario registrado en la sesión
if (isset($_SESSION['tipo_usuario'])) {
    $tipo_usuario = $_SESSION['tipo_usuario'];
    $empresa_usuario = isset($_SESSION['empresa']) ? $_SESSION['empresa'] : '';
} else {
    // Si no hay usuario registrado en la sesión, por ejemplo, si es la primera vez que el usuario accede después de iniciar sesión, establece un valor predeterminado o haz lo que sea necesario en tu aplicación
    $tipo_usuario = 'Tipo de Usuario Desconocido';
}

$imagen_empresa = '/Portal_Capacitacion/templates/img/';

// Determina la imagen del logo según la empresa del usuario
if ($empresa_usuario == 'Savisa') {
    $imagen_empresa .= 'syv.png';
} elseif ($empresa_usuario == 'Nevada') {
    $imagen_empresa .= 'nevada.png';
} elseif ($empresa_usuario == 'Coval') {
    $imagen_empresa .= 'coval.png';
} elseif ($empresa_usuario == 'Centenario') {
    $imagen_empresa .= 'ivc.png';
} else {
    // Si la empresa no está definida o no coincide con ninguna de las anteriores, muestra una imagen genérica
    //$imagen_empresa .= 'default_logo.png';
}

#echo $imagen_empresa;
#echo $empresa_usuario;
#echo $_SESSION['empresa'];

//echo "Tipo de Usuario: " . $tipo_usuario;

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <!-- Logo de la empresa a la izquierda -->
        <a class="navbar-brand" href="#">
            <img src="<?php echo $imagen_empresa; ?>" alt="Logo de la Empresa" width="50" height="50" class="d-inline-block align-top">
        </a>
        <li class="nav-item">
            <a class="nav-link" href="/Portal_Capacitacion/datos_usuario.php">Ver datos personales</a>
        </li>
        
        <!-- Botón de hamburguesa para dispositivos móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Contenido del navbar -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/Portal_Capacitacion/index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Portal_Capacitacion/notificaciones.php">Notificaciones</a>
                </li>
                <?php if ($tipo_usuario == 'Administrador'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/Portal_Capacitacion/administrar.php">Asignar cursos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Portal_Capacitacion/administrar_usuario.php">Gestionar usuarios</a>
                </li>
                <?php elseif ($tipo_usuario == 'Usuario Regular'): ?>
                <li class="nav-item">
                    <span class="nav-link disabled">Usuario Regular</span>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="/Portal_Capacitacion/cursos_aprobados.php">Ver Cursos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Portal_Capacitacion/logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


