<?php
// Inicia la sesión si no está iniciada
session_start();
// include 'error_control.php';

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

// Verificar si se ha pasado un ID válido para eliminar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    // Conexión a la base de datos
    $db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

   // Verificar si se seleccionaron usuarios para eliminar
    if (!empty($_POST['seleccionados'])) {
        // Convertir los ID de usuario seleccionados en una cadena separada por comas
        $usuarios_seleccionados = implode(",", $_POST['seleccionados']);

        // Preparar la sentencia SQL para eliminar los usuarios seleccionados
        $sql = "DELETE FROM public.usuarios WHERE id IN ($usuarios_seleccionados)";

        // Ejecutar la sentencia SQL
        if (pg_query($db, $sql)) {
            // Redirigir de vuelta a la página de listado de usuarios
            header("Location: administrar_usuario.php");
            exit();
        } else {
            echo "Error al intentar eliminar los usuarios seleccionados.";
        }
    } else {
        echo "No se ha seleccionado ningún usuario para eliminar.";
    } 
    // Cerrar la conexión a la base de datos
    pg_close($db);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>Crear Usuario</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/crear_usuario.css">

</head>
<body>

    <?php
include('db/db.php');

// Genera una contraseña aleatoria con los requisitos especificados
function generarContrasenaAleatoria() {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@,[*_-;?#$%&/()=';
    $longitud = 12;
    $contrasena = '';
    
    // Añade al menos un carácter de cada tipo
    $contrasena .= substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1);
    $contrasena .= substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 1);
    $contrasena .= substr(str_shuffle('0123456789'), 0, 1);
    $contrasena .= substr(str_shuffle('@,[*_-;?#$%&/()='), 0, 1);
    
    // Completa la contraseña con caracteres aleatorios
    $contrasena .= substr(str_shuffle($caracteres), 0, $longitud - 4);
    
    // Mezcla los caracteres para mayor aleatoriedad
    $contrasena = str_shuffle($contrasena);
    
    return $contrasena;
}

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conecta a la base de datos
    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    } catch (PDOException $e) {
        die("Error: No se pudo conectar a la base de datos. " . $e->getMessage());
    }

    // Obtiene los datos del formulario
    $rut = $_POST['rut'];
    $contrasena = $_POST['contrasena'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $correo = $_POST['correo'];
    $empresa = $_POST['empresa'];
    $nombre = $_POST['nombre'];
    $area = $_POST['area'];
    $cargo = $_POST['cargo'];

    // Prepara la consulta SQL para insertar un nuevo usuario
    $sql = "INSERT INTO public.usuarios (rut, contrasena, tipo_usuario, correo, empresa, nombre, area, cargo)
            VALUES (:rut, :contrasena, :tipo_usuario, :correo, :empresa, :nombre, :area, :cargo)";

    // Ejecuta la consulta SQL
    try {
        $contrasena = generarContrasenaAleatoria();

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->bindParam(':tipo_usuario', $tipo_usuario);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':empresa', $empresa);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':area', $area);
        $stmt->bindParam(':cargo', $cargo);
        $stmt->execute();
        echo "Usuario creado exitosamente.";
    } catch (PDOException $e) {
        die("Error al crear usuario: " . $e->getMessage());
    }
}
?>

        <div class="form1">
            <div class="inputBox1" style="text-align: left; margin-bottom:30px;">
                <a href="/Portal_Capacitacion/index.php"> <input type="submit" value="Volver"/> </a>
            </div>
        </div>
    <div class="container-div">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return validateForm();">
            <h2>Ingrese datos de nuevo usuario:</h2>
            <label for="rut">RUT:</label>
            <input type="text" id="rut" name="rut" placeholder="12345678-9" required><br>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" placeholder="********" required><br>
            <label for="tipo_usuario">Tipo de Usuario:</label>
            <select id="tipo_usuario" name="tipo_usuario" required>
                <option value="Administrador">Administrador</option>
                <option value="Usuario">Usuario</option>
            </select><br>
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" placeholder="juanperez@mail.com" required><br>
            <label for="empresa">Empresa:</label>
            <select id="empresa" name="empresa">
                <option value="Coval">Coval</option>
                <option value="Savisa">Savisa</option>
                <option value="Nevada">Nevada</option>
                <option value="Centenario">Centenario</option>
            </select><br>
            <label for="nombre">Nombre y Apellido:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ejemplo: Juan Pérez" required><br>
            <label for="area">Área:</label>
            <input type="text" id="area" name="area" placeholder="Ejemplo: Ventas" required><br>
            <label for="cargo">Cargo:</label>
            <input type="text" id="cargo" name="cargo" placeholder="Ejemplo: Gerente" required><br>
            <input class="inputBox2" type="submit" value="Crear Usuario">
        </form>
    </div>

    
    <div class="container-div1">
        <?php
        // Conexión a la base de datos
        $db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

        // Consulta SQL para obtener todos los usuarios
        $sql = "SELECT * FROM public.usuarios order by nombre";
        $result = pg_query($db, $sql);

        // Verificar si hay usuarios en la base de datos
        if (pg_num_rows($result) > 0) {
            // Crear un formulario para mostrar los usuarios y permitir la selección para eliminar
            echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            echo "<h2>Listado de Usuarios</h2>";
            echo "<div style='overflow-x: auto;'>"; // Agregar un contenedor con desplazamiento horizontal
            echo "<table border='1' style='width: 100%;'>"; // Hacer que la tabla sea de ancho variable
            echo "<tr><th>RUT</th><th>Nombre</th><th>Correo</th><th>Empresa</th><th>Seleccionar</th></tr>";

            // Mostrar cada usuario en una fila de la tabla con un checkbox para seleccionar
            while ($row = pg_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['rut'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['correo'] . "</td>";
                echo "<td>" . $row['empresa'] . "</td>";
                echo "<td><input type='checkbox' name='seleccionados[]' value='" . $row['id'] . "'></td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>"; // Cerrar el contenedor con desplazamiento horizontal
            echo "<br>";
            echo "<input type='submit' name='eliminar' value='Eliminar'>";
            echo "</form>";
        } else {
            echo "No se encontraron usuarios.";
        }
        ?>
    </div> 
    <script src="/Portal_Capacitacion/templates/js/createUser_Validate.js">
  </script>  

</body>
</html>
