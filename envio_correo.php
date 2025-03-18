<?php
require 'vendor/autoload.php';
include 'error_control.php';

if (isset($_POST['rut'])) {
    $rut = $_POST['rut'];

    include 'db/db.php';
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

    $query = "SELECT correo FROM usuarios WHERE rut = $1";
    $result = pg_query_params($conn, $query, array($rut));

    if (!$result) {
        error_log("Error en la consulta: " . pg_last_error($conn)); // Registra el error en el log
        echo '<script>alert("Error en la consulta."); window.location.href = "pwreset.php";</script>';
        exit;
    }

    if (pg_num_rows($result) > 0) {
        $usuario = pg_fetch_assoc($result);
        $correo = $usuario['correo'];

        $codigo = mt_rand(10000, 99999);

        session_start();
        $_SESSION["rut"] = $rut;
        $_SESSION['codigo_recuperacion'] = $codigo;

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->SMTPDebug = 2; // Habilita la depuración
        $mail->Debugoutput = function ($str, $level) {
            error_log("Debug: $str"); // Registra la salida de depuración en el log
        };
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Usa smtp.office365.com
        $mail->SMTPAuth   = true;
        $mail->Username   = 'acecorp-project@outlook.es';
        $mail->Password   = 'Wok18964';
        $mail->SMTPSecure = 'tls'; // Usa 'tls'
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Deshabilita la verificación de certificados (solo para pruebas)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('acecorp-project@outlook.es', 'No-Reply ACE Corporation');
        $mail->addAddress($correo);

        $mail->isHTML(true);
        $mail->Subject = 'Código de Recuperación de Clave';
        $mail->Body    = 'Tu código de recuperación de clave es: ' . $codigo;

        if ($mail->send()) {
            echo '<script>window.localStorage.setItem("arrivedFromEnvioCorreo", "true");</script>';
            echo '<script>alert("Se ha enviado un código de recuperación a tu correo electrónico."); window.location.href = "codigo.php";</script>';
        } else {
            error_log("Error al enviar el correo electrónico: " . $mail->ErrorInfo); // Registra el error en el log
            echo '<script>alert("Error al enviar el correo electrónico."); window.location.href = "pwreset.php";</script>';
        }
    } else {
        echo '<script>alert("No se encontró ningún usuario registrado con ese RUT."); window.location.href = "pwreset.php";</script>';
    }

    pg_close($conn);
} else {
    header('Location: pwreset.php');
    exit;
}
?>