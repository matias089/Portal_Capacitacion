<?php
session_start();

if (!isset($_SESSION['tipo_usuario'])) {

    header("Location: portada.html");
    exit();
}

include 'navbar.php';
include 'check_password.php';
include 'vendor/autoload.php';

$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$db) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$rut_usuario = $_SESSION['rut'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    $db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

    if (!empty($_POST['seleccionados'])) {
        $usuarios_seleccionados = implode(",", $_POST['seleccionados']);

        $sql = "DELETE FROM public.usuarios WHERE id IN ($usuarios_seleccionados)";

        if (pg_query($db, $sql)) {
            header("Location: administrar_usuario.php");
            exit();
        } else {
            echo "Error al intentar eliminar los usuarios seleccionados.";
        }
    } else {
        echo "No se ha seleccionado ningún usuario para eliminar.";
    } 
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

function generarContrasenaAleatoria() {
    $mayusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $minusculas = 'abcdefghijklmnopqrstuvwxyz';
    $numeros = '0123456789';
    $especiales = '@,[*_-;?#$%&/()=';

    $contrasena = '';
    $contrasena .= $mayusculas[rand(0, strlen($mayusculas) - 1)];
    $contrasena .= $minusculas[rand(0, strlen($minusculas) - 1)];
    $contrasena .= $numeros[rand(0, strlen($numeros) - 1)];
    $contrasena .= $especiales[rand(0, strlen($especiales) - 1)];

    $todos = $mayusculas . $minusculas . $numeros . $especiales;
    for ($i = 4; $i < 10; $i++) {
        $contrasena .= $todos[rand(0, strlen($todos) - 1)];
    }
    return str_shuffle($contrasena);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    } catch (PDOException $e) {
        die("Error: No se pudo conectar a la base de datos. " . $e->getMessage());
    }

    $rut = $_POST['rut'];
    $contrasena = generarContrasenaAleatoria();
    $tipo_usuario = $_POST['tipo_usuario'];
    $correo = $_POST['correo'];
    $empresa = $_POST['empresa'];
    $nombre = $_POST['nombre'];
    $area = $_POST['area'];
    $cargo = $_POST['cargo'];

    $sql = "INSERT INTO public.usuarios (rut, contrasena, tipo_usuario, correo, empresa, nombre, area, cargo)
            VALUES (:rut, :contrasena, :tipo_usuario, :correo, :empresa, :nombre, :area, :cargo)";

    try {
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
        echo 'Usuario creado exitosamente. ';

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.sendgrid.net';
        $mail->SMTPAuth   = true;
        $mail->Username = 'apikey'; // Literalmente la palabra 'apikey'
        $mail->Password = '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'; // API Key de SendGrid
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';


        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';

        $mail->setFrom('acecorp-projects@outlook.es', 'No-Reply ACE Corporation');
        $mail->addAddress($correo, $nombre);

        $mail->isHTML(true);
        $mail->Subject = 'Credenciales de acceso';
        $mail->Body    = "Hola $nombre,<br><br>Se ha creado tu cuenta con éxito. Aquí están tus credenciales:<br><br>RUT: $rut<br>Contraseña: $contrasena<br><br>Por favor, cambia tu contraseña después de iniciar sesión por primera vez.";

        if(!$mail->send()) {
            echo 'El mensaje no pudo ser enviado. ';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'El mensaje ha sido enviado';
        }

    } catch (PDOException $e) {
        die("Error: No se pudo ejecutar la consulta. " );
    }
    $pdo = null;
}
?>

<div class="container-div1">
    <h2>Crear Usuario</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="rut">RUT:</label>
        <input type="text" id="rut" name="rut" placeholder="Ejemplo: 12.345.678-9" required><br>
        <label for="tipo_usuario">Tipo de Usuario:</label>
        <select id="tipo_usuario" name="tipo_usuario">
            <option value="Administrador">Administrador</option>
            <option value="Usuario">Usuario</option>
        </select><br>
        <label for="correo">Correo Electrónico:</label>
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
    $db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

    $sql = "SELECT * FROM public.usuarios order by nombre";
    $result = pg_query($db, $sql);

    if (pg_num_rows($result) > 0) {
        echo "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
        echo "<h2>Listado de Usuarios</h2>";
        echo "<div style='overflow-x: auto;'>"; 
        echo "<table border='1' style='width: 100%;'>";
        echo "<tr><th>RUT</th><th>Nombre</th><th>Correo</th><th>Empresa</th><th>Seleccionar</th></tr>";

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
        echo "</div>";
        echo "<br>";
        echo "<input type='submit' name='eliminar' value='Eliminar'>";
        echo "</form>";
    } else {
        echo "No se encontraron usuarios.";
    }
    ?>
</div> 
<script src="/Portal_Capacitacion/templates/js/createUser_Validate.js"></script>
</body>
</html>