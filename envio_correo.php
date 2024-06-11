<?php
require 'vendor/autoload.php'; // Carga el autoload de Composer para PHPMailer
include 'error_control.php';
// Verifica si se recibió el RUT del formulario
if (isset($_POST['rut'])) {
    $rut = $_POST['rut'];

    // Incluye la configuración de la base de datos
    include 'db/db.php';

    // Conexión a la base de datos
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

    // Consulta para obtener el correo electrónico asociado al RUT
    $query = "SELECT correo FROM usuarios WHERE rut = $1";
    $result = pg_query_params($conn, $query, array($rut));

    // Verifica si hubo un error en la consulta
    if (!$result) {
        echo "Error en la consulta: " . pg_last_error($conn);
    }

    // Verifica si se encontró un correo electrónico asociado al RUT
    if (pg_num_rows($result) > 0) {
        $usuario = pg_fetch_assoc($result);
        $correo = $usuario['correo'];

        // Genera un código numérico de 5 caracteres
        $codigo = mt_rand(10000, 99999);

        // Guardar el código en una variable de sesión
        session_start();
        $_SESSION["rut"] = $rut;
        $_SESSION['codigo_recuperacion'] = $codigo;

        // Configura la conexión con el servidor SMTP y el envío del correo electrónico
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host       = 'smtp.office365.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'acecorp-projects@outlook.es';
        $mail->Password   = 'Wok18964';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('acecorp-projects@outlook.es', 'No-Reply ACE Corporation'); // Cambia esto por tu dirección de correo electrónico y nombre
        $mail->addAddress($correo); // Agrega el correo electrónico del destinatario

        $mail->isHTML(true);
        $mail->Subject = 'Código de Recuperación de Clave';
        $mail->Body    = 'Tu código de recuperación de clave es: ' . $codigo ;
        

        // Envía el correo electrónico
        if ($mail->send()) {
            // Establece la bandera en localStorage para indicar que el usuario llegó desde envio_correo.php
            echo '<script>window.localStorage.setItem("arrivedFromEnvioCorreo", "true");</script>';
            // Redirige al usuario a la página codigo.php
            echo '<script>alert("Se ha enviado un código de recuperación a tu correo electrónico."); window.location.href = "codigo.php";</script>';
        } else {
            echo '<script>alert("Error al enviar el correo electrónico: ' . $mail->ErrorInfo . '"); window.location.href = "pwreset.php";</script>';
        }
    } else {
        // Redirige a pwreset.php si no se encuentra el RUT
        echo '<script>alert("No se encontró ningún usuario registrado con ese RUT."); window.location.href = "pwreset.php";</script>';
    }

    // Cierra la conexión a la base de datos
    pg_close($conn);
} else {
    // Redirige al usuario de vuelta al formulario si no se proporcionó un RUT
    header('Location: pwreset.php');
    exit;
}
?>
