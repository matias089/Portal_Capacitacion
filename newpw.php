<?php
session_start();

include 'error_control.php';
// Verifica si la sesión está iniciada
if (!isset($_SESSION['rut'])) {
    header("Location: pwreset.php");
    exit();
}


$mensaje_html = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['rut'])) {
        echo "La variable de sesión 'rut' no está definida.";
        exit;
    }

    $rut = $_SESSION['rut'];
    $new_password1 = $_POST['new_password1'];
    $new_password2 = $_POST['new_password2'];

    // Función para validar la contraseña
    function validar_contrasena($password) {
        if (strlen($password) < 8) return false;
        if (!preg_match('/[A-Z]/', $password)) return false;
        if (!preg_match('/[a-z]/', $password)) return false;
        if (!preg_match('/[0-9]/', $password)) return false;
        if (!preg_match('/[\W]/', $password)) return false;
        return true;
    }

    if ($new_password1 != $new_password2) {
        $mensaje_html = "<div class='alert alert-danger' role='alert'>Las contraseñas no coinciden.</div>";
    } else if (!validar_contrasena($new_password1)) {
        $mensaje_html = "<div class='alert alert-danger' role='alert'>La contraseña no cumple con los requisitos de seguridad.</div>";
    } else {
        include('db/db.php');
        $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

        if (!$conn) {
            echo "Error en la conexión a la base de datos.";
            exit;
        }

        try {
            $fecha_actual = date("Y-m-d H:i:s");


            $query = "UPDATE usuarios SET contrasena = $1, ultima_modificacion_contrasena = $2 WHERE rut = $3";
            $result = pg_query_params($conn, $query, array($new_password1, $fecha_actual, $rut));

            if ($result) {
                $mensaje_html = "<div class='alert alert-success' role='alert'>La contraseña se actualizó correctamente.</div>";
                echo "<script>setTimeout(function() { window.location.href = 'login.php'; }, 2000);</script>";
            } else {
                $mensaje_html = "<div class='alert alert-danger' role='alert'>Error al actualizar la contraseña: " . pg_last_error($conn) . "</div>";
            }
            pg_close($conn);
        } catch (Exception $e) {
            $mensaje_html = "<div class='alert alert-danger' role='alert'>Error al actualizar la contraseña: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña</title>
    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/newpw.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <section>
        <div class="color"></div>
        <div class="color"></div>
        <div class="color"></div>
        <div class="box">
            <div class="square" style="--i:0;"></div>
            <div class="square" style="--i:1;"></div>
            <div class="square" style="--i:2;"></div>
            <div class="square" style="--i:3;"></div>
            <div class="square" style="--i:4;"></div>
            <div class="square" style="--i:5;"></div>
            <div class="square" style="--i:6;"></div>
             <div class="container" style="width: 28em;">
                <div class="form">
                    <h2>Nueva contraseña</h2>
                    <p>Su nueva contraseña debe contener al menos: una mayúscula, un carácter especial, un número y tener una longitud mínima de 8 caracteres.</p>
                    <form id="forgotPasswordForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm();">
                        <div class="inputBox">
                            <input type="password" id="new_password1" name="new_password1" placeholder="Ingrese su nueva contraseña" required autocomplete="off"/>
                        </div>
                        <div class="inputBox">
                            <input type="password" id="new_password2" name="new_password2" placeholder="Reingrese su contraseña" required autocomplete="off"/>
                        </div>
                        <div class="inputBox">
                            <input type="submit" value="Enviar" />
                        </div>
                    </form>
                    <?php
                    echo $mensaje_html;
                    ?>
                </div>
             </div>
        </div>
    </section>
</body>
</html>

